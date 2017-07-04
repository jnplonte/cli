<?php
namespace Iman\Command;

class imanHelpers
{
    public function _throwMessage($status='info', $text=null, $layout = null){
      if($layout){
        switch ($layout) {
          case 'alpha-error':
            return '<'.$status.'>name must be alphabetic characters</'.$status.'>';
          break;

          case 'num-error':
            return '<'.$status.'>name must be numeral characters</'.$status.'>';
          break;

          case 'invalid-user':
            return '<'.$status.'>user is not on our database</'.$status.'>';
          break;

          case 'denied-user':
            return '<'.$status.'>user is not allowed to widraw money more than his/her account balance, please apply for overdraft</'.$status.'>';
          break;

          default:
            return '<error>Internal Server Error</error>';
          break;
        }
      }else{
        if($text){
          return '<'.$status.'>'.$text.'</'.$status.'>';
        }else{
          return '<error>Internal Server Error</error>';
        }
      }
    }
}
