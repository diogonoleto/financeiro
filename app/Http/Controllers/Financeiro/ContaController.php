<?php

namespace App\Http\Controllers\Financeiro;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Financeiro\FinConta;
use App\Models\Financeiro\FinContaFatura;
use App\Models\Financeiro\FinMovimento;
use App\Models\Config\Banco\SisBanco;
use App\Models\Config\Financeiro\FinSistemaPagamento;
use App\Models\Financeiro\FinCategoria;
use Illuminate\Http\UploadedFile;
use Session;
use File;
use Storage;
use Gate;
use DB;

class ContaController extends Controller
{
    public function index()
    {
        if (Gate::denies('fin_conta_read'))
            return redirect()->back();
        $title = 'Contas';
        return view('financeiro.conta.index', compact('title'));
    }

    public function show($id)
    {
        if (Gate::denies('fin_conta_read')) {
            return redirect()->back();
        }
        $item = FinConta::find($id);
        if (count($item) == 0)
            return redirect()->route('financeiro');

        $title = $item->descricao;
        return view('financeiro.conta.show', compact('title', 'item'));
    }
    public function create(FinConta $item)
    {
        $this->authorize('fin_conta_create');
        $bancos = SisBanco::whereNull('deleted_at')->get();
        $contas = FinConta::whereNotIn('conta_tipo_id', [4, 5, 6, 7])->whereNull('deleted_at')->with('ContaTipo')->get();
        $sistemaPagamento = FinSistemaPagamento::all();
        return response(view('financeiro.conta.create', compact('item', 'bancos', 'sistemaPagamento', 'contas')));
    }
    public function store(Request $request, FinConta $conta, FinMovimento $movimento)
    {
        $this->authorize('fin_conta_create');
        $this->validate($request, [
            'descricao' => 'required|min:3|max:100|unique:fin_contas,descricao,NULL,id,deleted_at,NULL,sis_conta_id,' . Auth()->user()->sis_conta_id,
            'img'    => 'mimes:png,gif,jpeg,jpg,bmp'
        ]);
        $data = $request->saldo_data ? date('Y-m-d', strtotime(str_replace('/', '-', $request->saldo_data))) : date('Y-m-d');

        if (isset($request->banco_id)) {
            $img = SisBanco::find($request->banco_id)->img;
            $conta->banco_id = $request->banco_id;
        } else {
            $conta->banco_id = 0;
            $img = SisBanco::find(0)->img;
            if ($request->conta_tipo_id == 8)
                $img = "img/bancos/default5.png";
        }
        if ($request->padrao) {
            $itens = FinConta::where('padrao', 1)->update(['padrao' => 0]);
            $conta->padrao = $request->padrao;
        }
        $conta->descricao = $request->descricao;
        $conta->agencia = str_replace('-', '', $request->agencia);
        $conta->conta = str_replace('-', '', $request->conta);
        $conta->conta_tipo_id = $request->conta_tipo_id;
        $conta->bandeira = $request->bandeira;
        $conta->dia_vencimento = str_pad($request->dia_vencimento, 2, '0', STR_PAD_LEFT);
        $conta->limite = $request->limite ? str_replace(',', '.', str_replace('.', '', $request->limite)) : 0;
        $conta->img = $img;
        $conta->tipo_pessoa = $request->tipo_pessoa ? $request->tipo_pessoa : NULL;
        $conta->saldo = $request->saldo ? str_replace(',', '.', str_replace('.', '', $request->saldo)) : 0;
        $conta->saldo_data = $data;
        $conta->sistema_pagamento_id = $request->sistema_pagamento_id ? $request->sistema_pagamento_id : NULL;
        $conta->conta_id = $request->conta_id ? $request->conta_id : NULL;
        $resp = $conta->save();

        if ($resp) {
            $categoria = FinCategoria::where('nome', 'Saldo Inicial')->first();
            if (!$categoria) {
                $categoria = new FinCategoria;
                $categoria->tipo = 'Receita';
                $categoria->nome = 'Saldo Inicial';
                $categoria->descricao = 'Saldo Inicial';
                $categoria->save();
            }
            $movimento->categoria_id = $categoria->id;
            $movimento->empresa_id = $request->empresa_id ? $request->empresa_id : NULL;
            $movimento->conta_id = $conta->id;
            $movimento->user_id = auth()->user()->id;
            $movimento->descricao = 'Saldo Inicial (' . $request->descricao . ')';
            $movimento->valor = $conta->saldo;
            $movimento->valor_recebido = $conta->saldo;
            $movimento->data_baixa = $request->saldo_data ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->saldo_data))) : date('Y-m-d');
            $movimento->data_emissao = $data;
            $movimento->data_vencimento = $data;
            $movimento->save();
            if ($conta->conta_tipo_id == 4) {
                $now = date('Y-m-d');
                $data_vencimento = date('Y-m-' . $conta->dia_vencimento, strtotime($now));

                if ($now >= $data_vencimento)
                    $data_vencimento = date('Y-m-d', strtotime('+1 month', strtotime($data_vencimento)));
                $data_fechamento = date('Y-m-d', strtotime('-10 day', strtotime($data_vencimento)));
                $data_inicial = date('Y-m-d', strtotime('-1 month', strtotime($data_fechamento)));
                $data_inicial = date('Y-m-d', strtotime('+1 day', strtotime($data_inicial)));
                $fatura = new FinContaFatura;
                $fatura->conta_id = $conta->id;
                $fatura->data_inicial = $data_inicial;
                $fatura->data_fechamento = $data_fechamento;
                $fatura->data_vencimento = $data_vencimento;
                $fatura->status = 2;
                $fatura->save();
            }
            $sci = auth()->user()->sis_conta_id;
            if ($request->icone) {
                $d = date('YmdHis');
                $upload_success = Storage::put('public/contas/' . $sci . '/img/bancos/icone' . $d . '.png', Storage::disk('banco_file')->get($request->icone));
                $img = 'storage/contas/' . $sci . '/img/bancos/icone' . $d . '.png';
            } else {
                $file = $request->file('img');
                $d = date('YmdHis');
                if ($file) {
                    $ext = $file->getClientOriginalExtension();
                    $upload_success = $file->storeAs('public/contas/' . $sci . '/img/bancos/', 'icone' . $d . '.' . $ext);
                    $img = 'storage/contas/' . $sci . '/img/bancos/icone' . $d . '.' . $ext;
                }
            }
            if (isset($upload_success)) {
                $conta->img = $img;
                $conta->update();
            }
            return $conta;
        }
    }
    public function edit($id)
    {
        $this->authorize('fin_conta_update');
        $contas = FinConta::whereNotIn('conta_tipo_id', [4, 5, 6, 7])->whereNull('deleted_at')->with('ContaTipo')->get();
        $item = FinConta::where('id', $id)->with('banco')->first();
        $bancos = SisBanco::whereNull('deleted_at')->get();
        $sistemaPagamento = FinSistemaPagamento::all();
        return response(view('financeiro.conta.create', compact('item', 'bancos', 'sistemaPagamento', 'contas')));
    }
    public function update($id, Request $request, FinMovimento $movimento)
    {
        $this->authorize('fin_conta_update');
        $this->validate($request, [
            'descricao' => 'required|min:3|max:100|unique:fin_contas,descricao,' . $id . ',id,deleted_at,NULL,sis_conta_id,' . Auth()->user()->sis_conta_id,
            'img'    => 'mimes:png,gif,jpeg,jpg,bmp'
        ]);
        $data = $request->saldo_data ? date('Y-m-d', strtotime(str_replace('/', '-', $request->saldo_data))) : date('Y-m-d');
        $conta = FinConta::find($id);
        $dva = $conta->dia_vencimento;
        if (isset($request->banco_id)) {
            $img = SisBanco::find($request->banco_id)->img;;
            $conta->banco_id = $request->banco_id;
        } else {
            $conta->banco_id = 0;
            $img = $request->conta_tipo_id == 8 ? "img/bancos/default5.png" : SisBanco::find(0)->img;
        }
        if ($request->padrao) {
            FinConta::where('padrao', 1)->update(['padrao' => 0]);
            $conta->padrao = $request->padrao;
        }
        $sci = auth()->user()->sis_conta_id;
        if ($request->icone) {
            $d = date('YmdHis');
            $upload_success = Storage::put('public/contas/' . $sci . '/img/bancos/icone' . $d . '.png', Storage::disk('banco_file')->get($request->icone));
            $img = 'storage/contas/' . $sci . '/img/bancos/icone' . $d . '.png';
        } else {
            $file = $request->file('img');
            $d = date('YmdHis');
            if ($file) {
                $ext = $file->getClientOriginalExtension();
                $upload_success = $file->storeAs('public/contas/' . $sci . '/img/bancos/', 'icone' . $d . '.' . $ext);
                $img = 'storage/contas/' . $sci . '/img/bancos/icone' . $d . '.' . $ext;
            }
        }

        $conta->img = $img;
        $conta->descricao = $request->descricao;
        $conta->agencia = str_replace('-', '', $request->agencia);
        $conta->conta = str_replace('-', '', $request->conta);
        $conta->conta_tipo_id = $request->conta_tipo_id;
        $conta->bandeira = $request->bandeira;
        $conta->dia_vencimento = str_pad($request->dia_vencimento, 2, '0', STR_PAD_LEFT);
        $conta->limite = $request->limite ? str_replace(',', '.', str_replace('.', '', $request->limite)) : 0;

        $conta->tipo_pessoa = $request->tipo_pessoa ? $request->tipo_pessoa : NULL;
        $conta->saldo = $request->saldo ? str_replace(',', '.', str_replace('.', '', $request->saldo)) : 0;
        $conta->saldo_data = $data;
        $conta->sistema_pagamento_id = $request->sistema_pagamento_id ? $request->sistema_pagamento_id : NULL;
        $conta->conta_id = $request->conta_id ? $request->conta_id : NULL;
        $conta->update();

        if ($conta->conta_tipo_id == 4 && $dva != $request->dia_vencimento) {
            $fatura_atual = FinContaFatura::where('conta_id', $conta->id)->where('status', 2)->first();
            $fatura_futuras = FinContaFatura::where('conta_id', $conta->id)->where('status', 1)->orderBy('data_vencimento', 'asc')->get();
            $data_vencimento = date('Y-m-d', strtotime('+1 month', strtotime($fatura_atual->data_vencimento)));
            $data_vencimento = date('Y-m-' . $request->dia_vencimento, strtotime($data_vencimento));
            $data_fechamento = date('Y-m-d', strtotime('-10 day', strtotime($data_vencimento)));
            $data_inicial = date('Y-m-d', strtotime('+1 day', strtotime($fatura_atual->data_fechamento)));

            $data_vencimento_atual = date('Y-m-' . $request->dia_vencimento);
            $now = date('Y-m-d');
            if ($now >= $data_vencimento_atual)
                $data_vencimento_atual = date('Y-m-d', strtotime('+1 month', strtotime($data_vencimento_atual)));

            if (count($fatura_futuras) > 0) {
                foreach ($fatura_futuras as $ff) {
                    $ff->data_inicial = $data_inicial;
                    $ff->data_fechamento = $data_fechamento;
                    $ff->data_vencimento = $data_vencimento;
                    if ($data_vencimento < $data_vencimento_atual) {
                        $ff->status = 3;
                    } else if ($data_vencimento == $data_vencimento_atual) {
                        $ff->status = 2;

                        $fatura_atual->status = 3;
                        $fatura_atual->update();
                    } else {
                        $ff->status = 1;
                    }

                    $ff->update();
                    $data_vencimento = date('Y-m-d', strtotime('+1 month', strtotime($data_vencimento)));
                    $data_inicial = date('Y-m-d', strtotime('+1 day', strtotime($data_fechamento)));
                    $data_fechamento = date('Y-m-d', strtotime('-10 day', strtotime($data_vencimento)));
                }
            } else {
                $fatura_futura = new FinContaFatura;
                $fatura_futura->conta_id = $conta->id;
                $fatura_futura->data_inicial = $data_inicial;
                $fatura_futura->data_fechamento = $data_fechamento;
                $fatura_futura->data_vencimento = $data_vencimento;
                if ($data_vencimento < $data_vencimento_atual) {
                    $fatura_futura->status = 3;
                } else if ($data_vencimento == $data_vencimento_atual) {
                    $fatura_futura->status = 2;
                    $fatura_atual->status = 3;
                    $fatura_atual->update();
                } else {
                    $fatura_futura->status = 1;
                }
                $fatura_futura->save();
            }

            while ($data_vencimento <= $data_vencimento_atual) {
                $data_vencimento = date('Y-m-d', strtotime('+1 month', strtotime($data_vencimento)));
                $data_inicial = date('Y-m-d', strtotime('+1 day', strtotime($data_fechamento)));
                $data_fechamento = date('Y-m-d', strtotime('-10 day', strtotime($data_vencimento)));
                $fatura_futura = new FinContaFatura;
                $fatura_futura->conta_id = $conta->id;
                $fatura_futura->data_inicial = $data_inicial;
                $fatura_futura->data_fechamento = $data_fechamento;
                $fatura_futura->data_vencimento = $data_vencimento;
                if ($data_vencimento < $data_vencimento_atual) {
                    $fatura_futura->status = 3;
                } else if ($data_vencimento == $data_vencimento_atual) {
                    $fatura_futura->status = 2;

                    $fatura_atual->status = 3;
                    $fatura_atual->update();
                } else {
                    $fatura_futura->status = 1;
                }
                $fatura_futura->save();
            }
        }

        $categoria = FinCategoria::where('nome', 'Saldo Inicial')->first();
        $movimento = FinMovimento::where('categoria_id', $categoria->id)->where('conta_id', $conta->id)->first();
        $movimento->empresa_id = $request->empresa_id ? $request->empresa_id : NULL;
        $movimento->user_id = auth()->user()->id;
        $movimento->descricao = 'Saldo Inicial (' . $request->descricao . ')';
        $movimento->valor = $conta->saldo;
        $movimento->valor_recebido = $conta->saldo;
        $movimento->data_baixa = $request->saldo_data ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->saldo_data))) : date('Y-m-d');
        $movimento->data_emissao = $data;
        $movimento->data_vencimento = $data;
        $respMovimento = $movimento->update();
        if ($respMovimento) {
            return $conta;
        }
    }
    public function padrao(Request $request, $id)
    {
        if (Gate::denies('fin_conta_update')) {
            return redirect()->back();
        }
        $item = FinConta::find($id);
        $itens = FinConta::where('padrao', 1)->update(['padrao' => 0]);
        $item->padrao = 1;
        $item->update();
        if ($item)
            return redirect()->route('conta.index');
        else
            return redirect()->route('conta.index')->with(['error' => 'Falha ao editar!']);
    }
    public function destroy($id, Request $request)
    {
        if (Gate::denies('fin_conta_delete')) {
            return redirect()->back();
        }


        $conta = FinMovimento::select(DB::raw('fin_contas.descricao, SUM(IF(fin_categorias.tipo = "Receita", 1*fin_movimentos.valor , -1*fin_movimentos.valor )) as valor'))
            ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
            ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
            ->where('fin_contas.id', $id)
            ->whereNotNull('fin_movimentos.data_baixa')
            ->whereNull('fin_movimentos.deleted_at')
            ->groupBy('fin_contas.descricao')
            ->first();

        if ($request->delete_confirmar == 0) {
            $bancos = SisBanco::whereNull('deleted_at')->get();
            $sistemaPagamento = FinSistemaPagamento::all();
            $contas = FinConta::select('id', 'descricao', 'img')
                ->whereNotIn('conta_tipo_id', [4, 5, 6, 7])
                ->where('id', '!=', $id)
                ->whereNull('deleted_at')
                ->get();

            if (count($contas) > 0) {
                return response(view('financeiro.conta.delete', compact('id', 'bancos', 'sistemaPagamento', 'conta', 'contas')));
            } else {
                $error = ["error" => ["conta" => "A conta não pode ser excluir."]];
                return $error;
            }
        } else {

            if ($conta->valor != 0) {
                $saida = new FinMovimento;
                $categoria = FinCategoria::where('nome', 'Transferência de Saída')->first();
                if (!$categoria) {
                    $categoria = new FinCategoria;
                    $categoria->tipo = 'Despesa';
                    $categoria->nome = 'Transferência de Saída';
                    $categoria->descricao = 'Transferência de Saída';
                    $categoria->save();
                }
                $saida->categoria_id = $categoria->id;
                $saida->user_id  = auth()->user()->id;
                $saida->conta_id = $id;
                $saida->descricao = "Encerramento de Conta";
                $data = date('Y-m-d');
                $saida->data_vencimento = $data;
                $saida->data_emissao = $data;
                $saida->data_baixa = date('Y-m-d H:m:s');
                $saida->valor = $conta->valor;
                $saida->valor_recebido = $conta->valor;
                $saida->juro = 0;
                $saida->desconto = 0;
                $saida->save();

                $entrada = new FinMovimento;
                $entrada->movimento_id = $saida->id;
                $categoria = FinCategoria::where('nome', 'Transferência de Entrada')->first();
                if (!$categoria) {
                    $categoria = new FinCategoria;
                    $categoria->tipo = 'Receita';
                    $categoria->nome = 'Transferência de Entrada';
                    $categoria->descricao = 'Transferência de Entrada';
                    $categoria->save();
                }
                $entrada->categoria_id = $categoria->id;
                $entrada->user_id  = auth()->user()->id;
                $entrada->conta_id = $request->conta_transferencia_id;
                $entrada->descricao = "Encerramento de Conta";
                $entrada->data_vencimento = $data;
                $entrada->data_emissao = $data;
                $entrada->data_baixa = date('Y-m-d H:m:s');
                $entrada->valor = $conta->valor;
                $entrada->valor_recebido = $conta->valor;
                $entrada->desconto = 0;
                $entrada->juro = 0;
                $entrada->valor = $conta->valor;
                $entrada->valor_recebido = $conta->valor;
                $entrada->save();

                $saida->movimento_id = $entrada->id;
                $saida->update();
                $item['saida'] = $saida;
                $item['entrada'] = $entrada;
            }

            $movimentos = FinMovimento::where('id', $id)
                ->whereNull('fin_movimentos.data_baixa')
                ->whereNull('fin_movimentos.deleted_at')
                ->update(['conta_id' => $request->conta_transferencia_id]);





            // delete cartao de credito

            $contaDel = FinConta::find($id);
            $contaDel->deleted_at = Carbon::now();
            $contaDel->update();

            $item['contaDel'] = $contaDel;

            return $item;
        }
    }
    public function lista()
    {
        $this->authorize('fin_conta_read');

        $now = date('Y-m-d');
        $now = date('Y-m-d', strtotime('+1 month', strtotime($now)));
        $fatura_atuals = FinContaFatura::where('status', 2)->get();
        if (count($fatura_atuals) > 0) {
            foreach ($fatura_atuals as $fatura_atual) {
                $fatura_futuras = FinContaFatura::where('conta_id', $fatura_atual->conta_id)->where('status', 1)->orderBy('data_vencimento', 'asc')->with('conta')->get();
                if (count($fatura_futuras) > 0) {
                    foreach ($fatura_futuras as $fatura_futura) {
                        while ($fatura_futura->data_vencimento <= $now) {
                            $fa = FinContaFatura::where('conta_id', $fatura_futura->conta_id)->where('status', 2)->update(['status' => 3]);
                            $fatura_futura->status = 2;
                            $fatura_futura->update();
                            $ff = new FinContaFatura;
                            $ff->conta_id = $fatura_futura->conta_id;
                            $ff->data_inicial = date('Y-m-d', strtotime('+1 day', strtotime($fatura_futura->data_fechamento)));
                            $ff->data_vencimento = date('Y-m-d', strtotime('+1 month', strtotime($fatura_futura->data_vencimento)));
                            $ff->data_fechamento = date('Y-m-d', strtotime('-10 day', strtotime($ff->data_vencimento)));
                            $ff->status = 1;
                            $ff->save();
                            $fatura_futura = $ff;
                        }
                    }
                } else {
                    $fatura_futura = new FinContaFatura;
                    $fatura_futura->conta_id = $fatura_atual->conta_id;
                    $fatura_futura->data_inicial = date('Y-m-d', strtotime('+1 day', strtotime($fatura_atual->data_fechamento)));
                    $fatura_futura->data_vencimento = date('Y-m-d', strtotime('+1 month', strtotime($fatura_atual->data_vencimento)));
                    $fatura_futura->data_fechamento = date('Y-m-d', strtotime('-10 day', strtotime($fatura_futura->data_vencimento)));
                    $fatura_futura->status = 1;
                    $fatura_futura->save();
                }
            }
        }

        $order = Request('order');
        $order = Request()->has('order') ? $order : 'padrao';
        $sort = Request('sort');
        $sort = Request()->has('sort') ? $sort : 'desc';
        $search = Request('input-search');

        $i = FinConta::select(DB::raw('fin_contas.*, fin_conta_tipos.nome tnome, sis_bancos.nome bnome'))
            ->join('fin_conta_tipos', 'fin_conta_tipos.id', '=', 'fin_contas.conta_tipo_id')
            ->join('sis_bancos', 'sis_bancos.id', '=', 'fin_contas.banco_id');
        if ($order == 'tipo_conta') {
            $i->orderBy('fin_conta_tipos.nome', $sort);
        } else if ($order == 'banco') {
            $i->orderBy('sis_bancos.nome', $sort);
        } else if ($search || $search != '') {
            $i->where(function ($q) use ($search) {
                $q->where('fin_contas.descricao', 'LIKE', "%$search%")
                    ->orWhere('fin_conta_tipos.nome', 'LIKE', "%$search%")
                    ->orWhere('fin_contas.agencia', 'LIKE', "%$search%")
                    ->orWhere('fin_contas.conta', 'LIKE', "%$search%");
            })->orderBy('fin_contas.descricao', 'asc');
        } else {
            $i->orderBy($order, $sort)
                ->orderBy('tnome', $sort);
        }
        $i->whereNull('fin_contas.deleted_at')
            ->orderBy('fin_contas.descricao', 'asc');

        $itens = $i->paginate(28);
        return response(view('financeiro.conta.lista', compact('itens')));
    }
    public function contalista()
    {
        $id = Request('conta_id');
        $order = Request('order');
        $order = Request()->has('order') ? $order : 'data_emissao';
        $sort = Request('sort');
        $sort = Request()->has('sort') ? $sort : 'asc';
        $search = Request('input-search');

        $date = Request('date');
        $date = Request()->has('date') ? $date : date('Y-m');
        $from = date('Y-m-01', strtotime($date));
        $to = date('Y-m-t', strtotime($from));
        $conta = FinConta::find($id);
        if ($conta) {
            $fatura = FinContaFatura::where('conta_id', $conta->id)
                ->where(function ($q) use ($from, $to) {
                    $q->where('data_vencimento', '>=', $from)
                        ->Where('data_vencimento', '<=', $to);
                })
                ->whereNull('deleted_at')->first();
        }
        $a = FinConta::select(DB::raw('IFNULL(SUM(IF(c.tipo = "Receita", 1*m.valor , -1*m.valor )),0) as valor'))
            ->join('fin_movimentos as m', 'm.conta_id', '=', 'fin_contas.id')
            ->join('fin_categorias as c', 'c.id', '=', 'm.categoria_id')
            ->whereNull('m.deleted_at')
            ->where('fin_contas.id', $id);

        $t = FinConta::select(DB::raw('IFNULL(SUM(IF(c.tipo = "Receita", -1*m.valor , 1*m.valor )),0) as valor'))
            ->join('fin_movimentos as m', 'm.conta_id', '=', 'fin_contas.id')
            ->join('fin_categorias as c', 'c.id', '=', 'm.categoria_id')
            ->whereNull('m.deleted_at')
            ->where('fin_contas.id', $id);

        $i = FinMovimento::select(DB::raw('fin_movimentos.*, fin_categorias.tipo, fin_categorias.nome, fin_contas.img'))
            ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
            ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
            ->whereNull('fin_movimentos.deleted_at')
            ->where('fin_categorias.nome', '!=', 'Saldo Inicial')
            ->where('fin_movimentos.conta_id', $id);
        if (count($fatura) > 0) {
            $a->where('m.data_baixa', '<', $fatura->data_inicial);
            $i->where('fin_movimentos.conta_fatura_id', $fatura->id);
            $t->where('m.conta_fatura_id', $fatura->id);
        } else {
            $a->where('m.data_vencimento', '<', $from);
            $i->whereNull('fin_movimentos.conta_fatura_id')
                ->whereBetween('fin_movimentos.data_baixa', [$from, $to]);
            $t->whereBetween('m.data_vencimento', [$from, $to]);
        }
        if (isset($search) || $search != '') {
            $i->where('fin_movimentos.descricao', 'LIKE', "%$search%")
                ->orWhere('fin_contas.descricao', 'LIKE', "%$search%")
                ->orWhere('fin_categorias.nome', 'LIKE', "%$search%")
                ->orderBy('fin_movimentos.descricao', 'asc')
                ->orderBy('fin_categorias.nome', 'asc')
                ->orderBy('fin_contas.descricao', 'asc');
        }
        if ($order == 'descricao') {
            $i->orderBy('fin_movimentos.descricao', $sort);
        } else if ($order == 'tipo') {
            $i->orderBy('fin_categorias.tipo', $sort)
                ->orderBy('fin_movimentos.descricao', 'asc');
        } else if ($order == 'categoria_nome') {
            $i->orderBy('fin_categorias.nome', $sort)
                ->orderBy('fin_movimentos.descricao', 'asc');
        } else {
            if (count($fatura) > 0) {
                $i->orderBy($order, $sort)
                    ->orderBy('fin_movimentos.descricao', 'asc');
            } else {
                $i->orderBy('fin_movimentos.data_baixa', 'asc');
                \Log::info("54321:");
            }
        }

        $itens = $i->get();
        Carbon::setLocale('pt_BR');
        setlocale(LC_TIME, 'Portuguese');

        $anterior = $a->first();
        $total = $t->first();
        if (count($fatura) > 0) {
            return response(view('financeiro.conta.cartaocredito', compact('itens', 'anterior', 'total', 'fatura')));
        } else {
            return response(view('financeiro.conta.contacorrente', compact('itens', 'anterior', 'total')));
        }
    }
    public function banco()
    {
        return SisBanco::whereNull('deleted_at')->get();
    }
}
