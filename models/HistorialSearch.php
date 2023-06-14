<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Historial;
use Carbon\Carbon;
/**
 * HistorialSearch represents the model behind the search form about `app\models\Historial`.
 */
class HistorialSearch extends Historial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_historial', 'id_tabla_modificada', 'id_usuario_modifica','id_resolucion'], 'integer'],
            [['nombre_evento', 'fecha_modificacion', 'nombre_campo_modificado', 'valor_anterior_campo', 'valor_nuevo_campo', 'tabla_modificada','rango_fecha','fecha_hasta','fecha_desde'], 'safe'],
        ];
    }
    //$tiempo = Carbon::now('America/Bogota');
    public $rango_fecha,$fecha_desde ,$fecha_hasta;

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
        $query = Historial::find();

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
            'id_historial' => $this->id_historial,
            'id_tabla_modificada' => $this->id_tabla_modificada,
            //'fecha_modificacion' => $this->fecha_modificacion,
            'id_usuario_modifica' => $this->id_usuario_modifica,
            'id_resolucion' => $this->id_resolucion,
        ]);
        if (isset($this->rango_fecha) && !empty($this->rango_fecha)) {
            $lista = explode(' - ', $this->rango_fecha);
            if(count($lista) == 2){
              $this->fecha_desde = $lista[0];
              $this->fecha_hasta = $lista[1];
            }

        }
        $query->andFilterWhere(['like', 'nombre_evento', $this->nombre_evento])
            ->andFilterWhere(['like', 'nombre_campo_modificado', $this->nombre_campo_modificado])
            ->andFilterWhere(['like', 'valor_anterior_campo', $this->valor_anterior_campo])
            ->andFilterWhere(['like', 'valor_nuevo_campo', $this->valor_nuevo_campo])
            ->andFilterWhere(['like', 'tabla_modificada', $this->tabla_modificada])
            ->andFilterWhere(['>=', 'fecha_modificacion', $this->fecha_desde])
            ->andFilterWhere(['<=', 'fecha_modificacion', $this->fecha_hasta]);

        return $dataProvider;
    }
}
?>
