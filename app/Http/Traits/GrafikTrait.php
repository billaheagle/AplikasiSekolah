<?php

namespace App\Http\Traits;

trait GrafikTrait {
    private $flags = array('Pending', 'Done');
    private $flags2 = array('Expired', 'NotYetDue');
    private $color = array('#FF0000', '#00FF00');
    private $flagsKey = array('R' => 'Red', 'G' => 'Green');
    private $colorKey = array('R' => '#FF0000', 'G' => '#00FF00');
    private $subColor = '#A9A9A9';

    public function transposeSeries($datas, $types, $text, $drilldownLevel, $collect)
    {
        $chart = array();
        if($collect == 'status') {
            $flag = $this->flags;
            foreach ($datas as $key => $temp) $chart[$key] = [$temp->pending, $temp->done];
        }
        if($collect == 'duedate') {
            $flag = $this->flags2;
            foreach ($datas as $key => $temp) $chart[$key] = [$temp->expired, $temp->notYetDue];
        }
        array_unshift($chart, null);

        $series = array();
        foreach (call_user_func_array('array_map', $chart) as $key => $temp) {
            $series[$key]['name'] = $flag[$key];
            $series[$key]['text'] = $text;
            $series[$key]['color'] = $this->color[$key];
            $series[$key]['level'] = $drilldownLevel;
            foreach((array) $temp as $k => $temp2) {
                $series[$key]['data'][] = ['name' => $types[$k]->name, 'y' => $temp2, 'drilldown' => $types[$k]->code];
            }
        }
        return $series;
    }

    /*public function transposeDrilldownToSingleSeries($types, $datas, $drilldownLevel, $collect)
    {
        $series = array();
        foreach($types as $temp) {
            $id = $temp->code;
            $series[$id]['name'] = $temp->name;
            $series[$id]['text'] = $temp->name;
            $series[$id]['color'] = $this->subColor;
            $series[$id]['level'] = $drilldownLevel;
            foreach($datas[$temp->id] as $temp2) {
                $series[$id]['data'][] = ['name' => $temp2->name, 'y' => 1, 'color' => $this->colorKey[$temp2->flag], 'drilldown' => $temp2->code];
            }
        }
        return $series;
    }*/

    public function transposeDrilldownToMultipleSeries($type, $types, $datas, $dataTypes, $text, $drilldown, $drilldownLevel, $collect)
    {
        $series = array();
        foreach($types as $key => $temp) {
            $chart = array();
            if($collect == 'status') {
                $flag = $this->flags;
                foreach ($datas[$temp->id] as $key => $unit) $chart[$key] = [$unit->pending, $unit->done];
            }
            if($collect == 'duedate') {
                $flag = $this->flags2;
                foreach ($datas[$temp->id] as $key => $unit) $chart[$key] = [$unit->expired, $unit->notYetDue];
            }
            array_unshift($chart, null);

            foreach (call_user_func_array('array_map', $chart) as $key => $temp2) {
                if($drilldownLevel > 2) {
                    $id = $type . '-' . $temp->code . '-' . $flag[$key];
                } else {
                    $id = $temp->code . '-' . $flag[$key];
                }
                $series[$id]['name'] = $flag[$key];
                $series[$id]['text'] = $text;
                $series[$id]['color'] = $this->color[$key];
                $series[$id]['level'] = $drilldownLevel;
                foreach((array) $temp2 as $k => $temp3) {
                    if ($drilldown) {
                        $series[$id]['data'][] = ['name' => $dataTypes[$temp->id][$k]->name, 'y' => $temp3, 'drilldown' => $type . '-' .$dataTypes[$temp->id][$k]->code];
                    } else {
                        $series[$id]['data'][] = ['name' => $dataTypes[$temp->id][$k]->name, 'y' => $temp3];
                    }
                }
            }
        }
        return $series;
    }

    /*public function transposeDrilldownDetail($datas, $drilldownLevel)
    {
        $series = array();
        foreach ($datas as $temp) {
            $id = $temp->code;
            $series[$id]['name'] = $temp->name;
            $series[$id]['color'] = $this->subColor;
            $series[$id]['level'] = $drilldownLevel;
            $series[$id]['data'][] = ['name' => $this->flag[0], 'y' => $temp->r, 'color' => $this->color[0]];
            $series[$id]['data'][] = ['name' => $this->flag[1], 'y' => $temp->y, 'color' => $this->color[1]];
            $series[$id]['data'][] = ['name' => $this->flag[2], 'y' => $temp->g, 'color' => $this->color[2]];
        }
        return $series;
    }*/
}
