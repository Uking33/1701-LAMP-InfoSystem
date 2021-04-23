<?php
    $xmlDoc = new DomDocument();
    $xmlDoc->load('.\xmls\province_data.xml');
    $provinces = $xmlDoc->getElementsByTagName('province');    
    $provinceArr=array("");   //xmlArr
    $provinceJsonArr=array("");
    foreach($provinces as $province){
        $name1 = $province->attributes[0]->nodeValue;
        $citys = $province->getElementsByTagName('city');
        $cityArr=array($name1);
        $cityJsonArr=array(urlencode($name1));
        foreach($citys as $city){
            $name2 = $city->attributes[0]->nodeValue;
            $districts = $city->getElementsByTagName('district');
            $districtArr=array($name2);
            $districtJsonArr=array(urlencode($name2));
            foreach($districts as $district){
                $name3 = $district->attributes[0]->nodeValue;
                array_push($districtArr,$name3);
                array_push($districtJsonArr,urlencode($name3));
            }
            array_push($cityArr,$districtArr);
            array_push($cityJsonArr,$districtJsonArr);
        }
        array_push($provinceArr,$cityArr);
        array_push($provinceJsonArr,$cityJsonArr);
    }
    $provinceJsonArr=json_encode($provinceJsonArr);
?>