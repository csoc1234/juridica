<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Uhistorial;

/**
 * UhistorialSearch represents the model behind the search form of `app\models\Uhistorial`.
 */
class UhistorialSearch extends Uhistorial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_Uhistorial', 'U_id_usuario_modifica'], 'integer'],
            [['U_fecha_modificacion', 'U_nombre_eliminado', 'U_nombre_usuario_modifica'], 'safe'],
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
        $query = Uhistorial::find();

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
            'id_Uhistorial' => $this->id_Uhistorial,
            'U_id_usuario_modifica' => $this->U_id_usuario_modifica,
            'U_fecha_modificacion' => $this->U_fecha_modificacion,
        ]);

        $query->andFilterWhere(['like', 'U_nombre_eliminado', $this->U_nombre_eliminado])
            ->andFilterWhere(['like', 'U_nombre_usuario_modifica', $this->U_nombre_usuario_modifica]);

        return $dataProvider;
    }
}
