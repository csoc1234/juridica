<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Valores;

/**
 * ValoresSearch represents the model behind the search form about `app\models\Valores`.
 */
class ValoresSearch extends Valores
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_valores'], 'integer'],
            [['Descripcion_valor'], 'safe'],
            [['valor'], 'number'],
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
        $query = Valores::find();

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
            'id_valores' => $this->id_valores,
            'valor' => $this->valor,
        ]);

        $query->andFilterWhere(['like', 'Descripcion_valor', $this->Descripcion_valor]);

        return $dataProvider;
    }
}
