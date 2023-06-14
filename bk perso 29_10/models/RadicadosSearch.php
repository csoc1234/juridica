<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Radicados;

/**
 * RadicadosSearch represents the model behind the search form of `app\models\Radicados`.
 */
class RadicadosSearch extends Radicados
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_radicado', 'id_tipo_tramite', 'estado', 'id_entidad', 'id_usuario_tramita', 'id_usuario_creacion', 'sade','id_dignatario_tramite'], 'integer'],
            [['descripcion', 'n_radicado_interno' , 'ubicacion', 'fecha_creacion','id_tipo_resolucion','id_tipo_certificado'], 'safe'],
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
        $query = Radicados::find();

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
            'id_radicado' => $this->id_radicado,
            'id_tipo_tramite' => $this->id_tipo_tramite,
            'estado' => $this->estado,
            'id_entidad' => $this->id_entidad,
            'id_usuario_tramita' => $this->id_usuario_tramita,
            'id_usuario_creacion' => $this->id_usuario_creacion,
            'sade' => $this->sade,
            'fecha_creacion' => $this->fecha_creacion,
            'id_dignatario_tramite' => $this->id_dignatario_tramite
            //'n_radicado_interno' => $this->n_radicado_interno,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'ubicacion', $this->ubicacion])
            ->andFilterWhere(['like', 'n_radicado_interno', $this->n_radicado_interno]);

        return $dataProvider;
    }
}
