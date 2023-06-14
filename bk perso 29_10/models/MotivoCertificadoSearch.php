<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MotivoCertificado;

/**
 * MotivoCertificadoSearch represents the model behind the search form of `app\models\MotivoCertificado`.
 */
class MotivoCertificadoSearch extends MotivoCertificado
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_motivo'], 'integer'],
            [['nombre_motivo', 'descripcion_motivo'], 'safe'],
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
        $query = MotivoCertificado::find();

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
            'id_motivo' => $this->id_motivo,
        ]);

        $query->andFilterWhere(['like', 'nombre_motivo', $this->nombre_motivo])
            ->andFilterWhere(['like', 'descripcion_motivo', $this->descripcion_motivo]);

        return $dataProvider;
    }
}
