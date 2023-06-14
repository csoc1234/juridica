<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TipoEntidad;

/**
 * TipoEntidadSearch represents the model behind the search form of `app\models\TipoEntidad`.
 */
class TipoEntidadSearch extends TipoEntidad
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tipo_entidad'], 'integer'],
            [['tipo_entidad', 'codigo_trd','revision','gaceta'], 'safe'],
            [['activo'], 'boolean'],
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
        $query = TipoEntidad::find();

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
            'id_tipo_entidad' => $this->id_tipo_entidad,
            'activo' => $this->activo,
        ]);

        $query->andFilterWhere(['like', 'tipo_entidad', $this->tipo_entidad])
            ->andFilterWhere(['like', 'codigo_trd', $this->codigo_trd])
            ->andFilterWhere(['like', 'revision', $this->revision])
            ->andFilterWhere(['like', 'gaceta', $this->gaceta]);

        return $dataProvider;
    }
}
