<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Dignatarios;

/**
 * DignatariosSearch represents the model behind the search form about `app\models\Dignatarios`.
 */
class DignatariosSearch extends Dignatarios
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_dignatario', 'id_municipio_expedicion', 'id_entidad', 'id_cargo', 'id_grupo_cargos'], 'integer'],
            [['cedula_dignatario', 'nombre_dignatario', 'fecha_ingreso', 'tarjeta_profesiona', 'inicio_periodo', 'fin_periodo'], 'safe'],
            [['estado'], 'boolean'],
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
        $query = Dignatarios::find();

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
            'id_dignatario' => $this->id_dignatario,
            'estado' => $this->estado,
            'id_municipio_expedicion' => $this->id_municipio_expedicion,
            'fecha_ingreso' => $this->fecha_ingreso,
            'id_entidad' => $this->id_entidad,
            'id_cargo' => $this->id_cargo,
            'id_grupo_cargos' => $this->id_grupo_cargos,
            'inicio_periodo' => $this->inicio_periodo,
            'fin_periodo' => $this->fin_periodo,
        ]);

        $query->andFilterWhere(['like', 'cedula_dignatario', $this->cedula_dignatario])
            ->andFilterWhere(['like', 'nombre_dignatario', $this->nombre_dignatario])
            ->andFilterWhere(['like', 'tarjeta_profesiona', $this->tarjeta_profesiona]);

        return $dataProvider;
    }
}
