<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Resoluciones;

/**
 * ResolucionesSearch represents the model behind the search form about `app\models\Resoluciones`.
 */
class ResolucionesSearch extends Resoluciones
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_resolucion', 'ano_resolucion', 'numero_resolucion', 'id_tipo_resolucion', 'id_entidad','id_radicado','id_historial'], 'integer'],
            [['fecha_creacion', 'nombre_entidad'], 'safe'],
        ];
    }

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
        $query = Resoluciones::find();

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
            'id_resolucion' => $this->id_resolucion,
            'ano_resolucion' => $this->ano_resolucion,
            'numero_resolucion' => $this->numero_resolucion,
            'fecha_creacion' => $this->fecha_creacion,
            'id_tipo_resolucion' => $this->id_tipo_resolucion,
            'id_entidad' => $this->id_entidad,
            'id_historial' => $this->id_historial,
            'id_radicado' => $this->id_radicado,
        ]);

        $query->andFilterWhere(['like', 'nombre_entidad', $this->nombre_entidad]);

        return $dataProvider;
    }
}
