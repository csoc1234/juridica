<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Validacion;

/**
 * ValidacionSearch represents the model behind the search form of `app\models\Validacion`.
 */
class ValidacionSearch extends Validacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_validacion', 'id_radicado', 'estado', 'IDT_tramite'], 'integer'],
            [['fecha_creacion', 'fecha_sistem', 'codigo_h', 'codigo_cons', 'archivo'], 'safe'],
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
        $query = Validacion::find();

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
            'id_validacion' => $this->id_validacion,
            'id_radicado' => $this->id_radicado,
            'estado' => $this->estado,
            'IDT_tramite' => $this->IDT_tramite,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_sistem' => $this->fecha_sistem,
            'codigo_cons' => $this->codigo_cons,
        ]);

        $query->andFilterWhere(['like', 'codigo_h', $this->codigo_h])
            ->andFilterWhere(['like', 'codigo_cons', $this->codigo_cons])
            ->andFilterWhere(['like', 'archivo', $this->archivo]);

        return $dataProvider;
    }
}
