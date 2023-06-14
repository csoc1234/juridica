<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PreciosTramites;

/**
 * PreciosTramitesSearch represents the model behind the search form about `app\models\PreciosTramites`.
 */
class PreciosTramitesSearch extends PreciosTramites
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_precio_tramite', 'precio'], 'integer'],
            [['tramite'], 'safe'],
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
        $query = PreciosTramites::find();

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
            'id_precio_tramite' => $this->id_precio_tramite,
            'precio' => $this->precio,
        ]);

        $query->andFilterWhere(['like', 'tramite', $this->tramite]);

        return $dataProvider;
    }
}
