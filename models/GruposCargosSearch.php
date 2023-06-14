<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GruposCargos;

/**
 * GruposCargosSearch represents the model behind the search form about `app\models\GruposCargos`.
 */
class GruposCargosSearch extends GruposCargos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_grupo_cargos'], 'integer'],
            [['nombre_grupo_cargo'], 'safe'],
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
        $query = GruposCargos::find();

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
            'id_grupo_cargos' => $this->id_grupo_cargos,
        ]);

        $query->andFilterWhere(['like', 'nombre_grupo_cargo', $this->nombre_grupo_cargo]);

        return $dataProvider;
    }
}
