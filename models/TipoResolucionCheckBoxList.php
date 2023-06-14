<?php

namespace app\models;
use Yii;

class TipoResolucionCheckBoxList extends Radicados
{
     private $arrayTipoResolucionCombinada;
     private $arrayTipoReformaEstatutos;     
     private $arrayTipoRegistroLibro;

     public function getArrayTipoResolucionCombinada()
     {
          
          if($this->arrayTipoResolucionCombinada == null) 
          {
                $this->arrayTipoResolucionCombinada = $this->id_tipo_resolucion_combinada;
          }
          return $this->arrayTipoResolucionCombinada;
     }

      
      public function setArrayTipoResolucionCombinada($value)
      {
           $this->arrayTipoResolucionCombinada = $value;
      }

      public function getArrayTipoReformaEstatutos()
     {
          
          if($this->arrayTipoReformaEstatutos == null) 
          {
                $this->arrayTipoReformaEstatutos = $this->id_tipo_reforma_estatutaria;
          }
          return $this->arrayTipoReformaEstatutos;
     }

      
      public function setArrayTipoReformaEstatutos($value)
      {
           $this->arrayTipoReformaEstatutos = $value;
      }

      public function getArrayTipoRegistroLibro()
      {
           
           if($this->arrayTipoRegistroLibro == null) 
           {
                 $this->arrayTipoRegistroLibro = $this->id_tipo_registro_libro;
           }
           return $this->arrayTipoRegistroLibro;
      }
 
       
       public function setArrayTipoRegistroLibro($value)
       {
            $this->arrayTipoRegistroLibro = $value;
       }
      
      public function rules()
      {
          return array_merge(parent::rules(), [
                   [['id_tipo_resolucion_combinada','id_tipo_reforma_estatutaria','id_tipo_registro_libro','id_entidadcg'], 'safe'],

          ]);
       }       
}

