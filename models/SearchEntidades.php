<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Entidades;

/**
 * SearchEntidades represents the model behind the search form about `app\models\Entidades`.
 */
class SearchEntidades extends Entidades
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_entidad', 'personeria_año', 'personeria_n', 'municipio_entidad', 'id_tipo_entidad', 'id_clase_entidad'], 'integer'],
            [['nombre_entidad', 'razon_entidad', 'fecha_reconocimiento', 'direccion_entidad', 'telefono_entidad', 'fax_entidad', 'email_entidad', 'objetivos_entidad', 'fecha_estatutos', 'ubicacion_archivos_entidad', 'fecha_gaceta', 'datos_digitales'], 'safe'],
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
        $query = Entidades::find();

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
            'id_entidad' => $this->id_entidad,
            'personeria_año' => $this->personeria_año,
            'personeria_n' => $this->personeria_n,
            'fecha_reconocimiento' => $this->fecha_reconocimiento,
            'municipio_entidad' => $this->municipio_entidad,
            'id_tipo_entidad' => $this->id_tipo_entidad,
            'id_clase_entidad' => $this->id_clase_entidad,
            'fecha_estatutos' => $this->fecha_estatutos,
            'fecha_gaceta' => $this->fecha_gaceta,
        ]);

        $query->andFilterWhere(['like', 'nombre_entidad', $this->nombre_entidad])
            ->andFilterWhere(['like', 'razon_entidad', $this->razon_entidad])
            ->andFilterWhere(['like', 'direccion_entidad', $this->direccion_entidad])
            ->andFilterWhere(['like', 'telefono_entidad', $this->telefono_entidad])
            ->andFilterWhere(['like', 'fax_entidad', $this->fax_entidad])
            ->andFilterWhere(['like', 'email_entidad', $this->email_entidad])
            ->andFilterWhere(['like', 'objetivos_entidad', $this->objetivos_entidad])
            ->andFilterWhere(['like', 'ubicacion_archivos_entidad', $this->ubicacion_archivos_entidad])
            ->andFilterWhere(['like', 'datos_digitales', $this->datos_digitales]);

        return $dataProvider;
    }
}
