<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Entidadcamaracomercio;

/**
 * EntidadcamaracomercioSearch represents the model behind the search form of `app\models\Entidadcamaracomercio`.
 */
class EntidadcamaracomercioSearch extends Entidadcamaracomercio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_entidad_camara',  'id_municipio', 'id_municipio_camara', 'cedula_representante', 'nit_entidad'], 'integer'],
            [['nombre_entidad_camara', 'direccion_entidad', 'nombre_representante'], 'safe'],
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
        $query = Entidadcamaracomercio::find();

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
            'id_entidad_camara' => $this->id_entidad_camara,
            'id_municipio' => $this->id_municipio,
            'id_municipio_camara' => $this->id_municipio_camara,
            'cedula_representante' => $this->cedula_representante,
            'nit_entidad' => $this->nit_entidad,
        ]);

        $query->andFilterWhere(['like', 'nombre_entidad_camara', $this->nombre_entidad_camara])
            ->andFilterWhere(['like', 'direccion_entidad', $this->direccion_entidad])
            ->andFilterWhere(['like', 'nombre_representante', $this->nombre_representante]);

        return $dataProvider;
    }
}
