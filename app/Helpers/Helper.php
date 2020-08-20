<?php
namespace App\Helpers;
class Helper
{
  public static function generatePassword()
  {
    $capitalLetters = str_shuffle('abcdefghijklmnopqrstuvwxyz');
    $smallLetters = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
    $numbers = (((date('Ymd') / 12) * 24) + mt_rand(800, 9999));
    $numbers .= 1234567890;
    $specialCharacters = str_shuffle('!@#$%*-');
    $characters = $capitalLetters.$smallLetters.$numbers.$specialCharacters;
    $password = substr(str_shuffle($characters), 0, 8);
    return $password;
  }

  public static function sanitizeString($string)
  {
    $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º', 'Ã', 'Õ', '&');
    $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', 'A', 'O', '');
    return str_replace($what, $by, $string);
  }

  public static function createUrl($string)
  {
    $url = sanitizeString($string);
    return str_replace(' ', '-', strtolower(filter_var($url, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) );
  }

  public static function cpfcnpj($cnpj)
  {
    if (!$cnpj) {
      return '';
    }
    if (strlen($cnpj) == 11) {
      return substr($cnpj, 0, 3) . '.' . substr($cnpj, 3, 3) . '.' . substr($cnpj, 6, 3) . '-' . substr($cnpj, 9);
    } else if (strlen($cnpj) == 14) {
      return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
    }
    return $cnpj;
  }

  public static function fone($fone)
  {
    if (!$fone) {
      return '';
    }
    if (strlen($fone) == 10) {
      return '(' . substr($fone, 0, 2) . ')' . substr($fone, 2, 4) . '-' . substr($fone, 6);
    }
    if (strlen($fone) == 11) {
      return '(' . substr($fone, 0, 2) . ')' . substr($fone, 2, 5) . '-' . substr($fone, 7);
    }
    return $fone;
  }

  public static function dataRecorrente($data, $ciclo)
  {
    $mes = date('Y-m', strtotime(str_replace("/", "-", $data)));
    $dia = date('d', strtotime(str_replace("/", "-", $data)));
    $mes_ano = date('Y-m', strtotime($ciclo, strtotime($mes)));
    $data_q = date('Y-m-d', strtotime($mes_ano.'-'.$dia));
    $data = $mes_ano.'-'.$dia == $data_q ? $data_q: date('Y-m-t', strtotime($mes_ano));
    return $data;
  }

  // somar_dias_uteis('09/04/2009','','');
  // public static function somar_dias_uteis($str_data, $int_qtd_dias_somar, $feriados) {

  //   $str_data = str_replace('/', '-', $str_data);
  //   $str_data = date('Y-m-d', strtotime($str_data)

  //   // chama a funcao que calcula a pascoa  
  //   $pascoa_dt = dataPascoa(date('Y'));
  //   $aux_p = explode("/", $pascoa_dt);
  //   $aux_dia_pas = $aux_p[0];
  //   $aux_mes_pas = $aux_p[1];
  //   $pascoa = "$aux_mes_pas"."-"."$aux_dia_pas"; // crio uma data somente como mes e dia

  //   // chama a funcao que calcula o carnaval  
  //   $carnaval_dt = dataCarnaval(date('Y'));
  //   $aux_carna = explode("/", $carnaval_dt);
  //   $aux_dia_carna = $aux_carna[0];
  //   $aux_mes_carna = $aux_carna[1];
  //   $carnaval = "$aux_mes_carna"."-"."$aux_dia_carna"; 

  //   // chama a funcao que calcula corpus christi  
  //   $CorpusChristi_dt = dataCorpusChristi(date('Y'));
  //   $aux_cc = explode("/", $CorpusChristi_dt);
  //   $aux_cc_dia = $aux_cc[0];
  //   $aux_cc_mes = $aux_cc[1];
  //   $Corpus_Christi = "$aux_cc_mes"."-"."$aux_cc_dia"; 

  //   // chama a funcao que calcula a sexta feira santa 
  //   $sexta_santa_dt = dataSextaSanta(date('Y'));
  //   $aux = explode("/", $sexta_santa_dt);
  //   $aux_dia = $aux[0];
  //   $aux_mes = $aux[1];
  //   $sexta_santa = "$aux_mes"."-"."$aux_dia"; 

  //   $feriados = array("01-01", $carnaval, $sexta_santa, $pascoa, $Corpus_Christi, "04-21", "05-01", "06-12" ,"07-09", "07-16", "09-07", "10-12", "11-02", "11-15", "12-24", "12-25", "12-31");

  //   $array_data = explode('-', $str_data);
  //   $count_days = 0;
  //   $int_qtd_dias_uteis = 0;

  //   while ( $int_qtd_dias_uteis < $int_qtd_dias_somar ) {
  //     $count_days++;
  //     $day = date('m-d',strtotime('+'.$count_days.'day',strtotime($str_data))); 
  //     if(($dias_da_semana = gmdate('w', strtotime('+'.$count_days.' day', gmmktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0]))) ) != '0' && $dias_da_semana != '6' && !in_array($day,$feriados)) {
  //       $int_qtd_dias_uteis++;
  //     }
  //   }
  //   return gmdate('d/m/Y',strtotime('+'.$count_days.' day',strtotime($str_data)));
  // }

  // public static function dataPascoa($ano=false, $form="d/m/Y") {
  //   $ano=$ano?$ano:date("Y");
  //   if ($ano<1583) { 
  //     $A = ($ano % 4);
  //     $B = ($ano % 7);
  //     $C = ($ano % 19);
  //     $D = ((19 * $C + 15) % 30);
  //     $E = ((2 * $A + 4 * $B - $D + 34) % 7);
  //     $F = (int)(($D + $E + 114) / 31);
  //     $G = (($D + $E + 114) % 31) + 1;
  //     return date($form, mktime(0,0,0,$F,$G,$ano));
  //   }
  //   else {
  //     $A = ($ano % 19);
  //     $B = (int)($ano / 100);
  //     $C = ($ano % 100);
  //     $D = (int)($B / 4);
  //     $E = ($B % 4);
  //     $F = (int)(($B + 8) / 25);
  //     $G = (int)(($B - $F + 1) / 3);
  //     $H = ((19 * $A + $B - $D - $G + 15) % 30);
  //     $I = (int)($C / 4);
  //     $K = ($C % 4);
  //     $L = ((32 + 2 * $E + 2 * $I - $H - $K) % 7);
  //     $M = (int)(($A + 11 * $H + 22 * $L) / 451);
  //     $P = (int)(($H + $L - 7 * $M + 114) / 31);
  //     $Q = (($H + $L - 7 * $M + 114) % 31) + 1;
  //     return date($form, mktime(0,0,0,$P,$Q,$ano));
  //   }
  // }
  // public static function dataCarnaval($ano=false, $form="d/m/Y") {
  //   $ano=$ano?$ano:date("Y");
  //   $a=explode("/", dataPascoa($ano));
  //   return date($form, mktime(0,0,0,$a[1],$a[0]-47,$a[2]));
  // }
  // public static function dataCorpusChristi($ano=false, $form="d/m/Y") {
  //   $ano=$ano?$ano:date("Y");
  //   $a=explode("/", dataPascoa($ano));
  //   return date($form, mktime(0,0,0,$a[1],$a[0]+60,$a[2]));
  // }
  // public static function dataSextaSanta($ano=false, $form="d/m/Y") {
  //   $ano=$ano?$ano:date("Y");
  //   $a=explode("/", dataPascoa($ano));
  //   return date($form, mktime(0,0,0,$a[1],$a[0]-2,$a[2]));
  // } 

}