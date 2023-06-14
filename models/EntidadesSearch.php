<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Entidades;

/**
 * EntidadesSearch represents the model behind the search form of `app\models\Entidades`.
 */
class EntidadesSearch extends Entidades
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_entidad', 'personeria_year', 'personeria_n', 'municipio_entidad', 'id_tipo_entidad', 'id_clase_entidad', 'estado_entidad'], 'integer'],
            [['nombre_entidad', 'fecha_reconocimiento', 'direccion_entidad', 'telefono_entidad', 'email_entidad', 'ubicacion_archivos_entidad', 'datos_digitales','rango_fecha','fecha_hasta','fecha_desde','fecha_estatutos'], 'safe'],
        ];
    }

    public $rango_fecha,$fecha_desde ,$fecha_hasta;
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Entidades::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_entidad' => $this->id_entidad,
            'personeria_year' => $this->personeria_year,
            'personeria_n' => $this->personeria_n,
            //'fecha_reconocimiento' => $this->fecha_reconocimiento,
            'municipio_entidad' => $this->municipio_entidad,
            'id_tipo_entidad' => $this->id_tipo_entidad,
            'id_clase_entidad' => $this->id_clase_entidad,
            'estado_entidad' => $this->estado_entidad,
            'fecha_estatutos' => $this->fecha_estatutos,
        ]);
         if (isset($this->rango_fecha) && !empty($this->rango_fecha)) {
            $lista = explode(' - ', $this->rango_fecha);
            if(count($lista) == 2){
              $this->fecha_desde = $lista[0];
              $this->fecha_hasta = $lista[1];
            }

        }
        $query->andFilterWhere(['like', 'nombre_entidad', $this->nombre_entidad])
            ->andFilterWhere(['like', 'direccion_entidad', $this->direccion_entidad])
            ->andFilterWhere(['like', 'telefono_entidad', $this->telefono_entidad])
            ->andFilterWhere(['like', 'email_entidad', $this->email_entidad])
            ->andFilterWhere(['>=', 'fecha_reconocimiento', $this->fecha_desde])
            ->andFilterWhere(['<=', 'fecha_reconocimiento', $this->fecha_hasta])
            ->andFilterWhere(['like', 'ubicacion_archivos_entidad', $this->ubicacion_archivos_entidad])
            ->andFilterWhere(['like', 'datos_digitales', $this->datos_digitales])
           
            ;

        return $dataProvider;
    }
}
