<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TramitePublico;

/**
 * TramitePublicoSearch represents the model behind the search form of `app\models\TramitePublico`.
 */
class TramitePublicoSearch extends TramitePublico
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tramite_publico', 'cedula_tramitePublico', 'telefono_tramitePublico', 'telefono_entidad_tramitePublico', 'cantidad_tipocert_tramite_publico'], 'integer'],
            [['fecha_tramitePublico', 'dirigido_tramitePublico', 'nombre_solicitante_tramitePublico', 'direccion_tramitePublico', 'email_tramitePublico', 'nombre_entidad_tramitePublico', 'direccion_entidad_tramitePublico', 'email_entidad_tramitePublico', 'nombre_represeLegal_tramitePublico', 'motivo_solicitud_tramitePublico','otrosMotivoCert_tramite_publico', 'clase_solicitud_tramitePublico', 'tipocertificado_tramite_publico'], 'safe'],
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
        $query = TramitePublico::find();

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
            'id_tramite_publico' => $this->id_tramite_publico,
            'fecha_tramitePublico' => $this->fecha_tramitePublico,
            'cedula_tramitePublico' => $this->cedula_tramitePublico,
            'telefono_tramitePublico' => $this->telefono_tramitePublico,
            'telefono_entidad_tramitePublico' => $this->telefono_entidad_tramitePublico,
            'cantidad_tipocert_tramite_publico' => $this->cantidad_tipocert_tramite_publico,
        ]);

        $query->andFilterWhere(['like', 'dirigido_tramitePublico', $this->dirigido_tramitePublico])
            ->andFilterWhere(['like', 'nombre_solicitante_tramitePublico', $this->nombre_solicitante_tramitePublico])
            ->andFilterWhere(['like', 'direccion_tramitePublico', $this->direccion_tramitePublico])
            ->andFilterWhere(['like', 'email_tramitePublico', $this->email_tramitePublico])
            ->andFilterWhere(['like', 'nombre_entidad_tramitePublico', $this->nombre_entidad_tramitePublico])
            ->andFilterWhere(['like', 'direccion_entidad_tramitePublico', $this->direccion_entidad_tramitePublico])
            ->andFilterWhere(['like', 'email_entidad_tramitePublico', $this->email_entidad_tramitePublico])
            ->andFilterWhere(['like', 'nombre_represeLegal_tramitePublico', $this->nombre_represeLegal_tramitePublico])
            ->andFilterWhere(['like', 'motivo_solicitud_tramitePublico', $this->motivo_solicitud_tramitePublico])
            ->andFilterWhere(['like', 'otrosMotivoCert_tramite_publico', $this->otrosMotivoCert_tramite_publico])
            ->andFilterWhere(['like', 'clase_solicitud_tramitePublico', $this->clase_solicitud_tramitePublico])
            ->andFilterWhere(['like', 'tipocertificado_tramite_publico', $this->tipocertificado_tramite_publico]);

        return $dataProvider;
    }
}
