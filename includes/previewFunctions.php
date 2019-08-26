<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08/08/18
 * Time: 08:44 م
 */

function jsonCodeToPreviewElements($json){

}

function getCardPreview($pageId,$flowId,$cardId){

    require __DIR__.'/vendor/autoload.php';
    $mongoClient = new MongoDB\Client;
    $collection = $mongoClient->clevermessenger->flows;
    $flowId = getFlowData($pageId,$flowId)->id;
    $json = array();

    if ($flowId) {

        $document = $collection->findOne(['flow' => $flowId]);

        $data = $document->data;


        if (isset($data->_cards)){
            foreach ($data->_cards as $card){
                if ($card->_id == $cardId) {
                    $json[] = $card->_json;
                    break;
                }
            }
        }

    }

    return $json;


}

function getFlowPreview($pageId,$flowId){

    require __DIR__.'/vendor/autoload.php';
    $mongoClient = new MongoDB\Client;
    $collection = $mongoClient->clevermessenger->flows;
    $flowId = getFlowData($pageId,$flowId)->id;
    $usedCards = array();

    if ($flowId) {

        $document = $collection->findOne(['flow' => $flowId]);

        $data = $document->data;
        $nextCard = $document->data->_firstCard;
        $linkedList = array();

        while (isset($data->_cards->{$nextCard}->_json)){
            if (!in_array($nextCard,$usedCards)) {

                $usedCards[] = $nextCard;
            }
            else
                break;
           $linkedList[] = $document->data->_cards->{$nextCard}->_json;
           if (!isset($document->data->data->links->{$nextCard}->delayType)) break;
           $delayType = $document->data->data->links->{$nextCard}->delayType;
           if (in_array($delayType,["seconds","minutes","hours","days"])) {
                $delayData = new stdClass();
               $delayData->delay_data = new stdClass();
               $delayData->delay_data->delay_value = $document->data->data->links->{$nextCard}->delayValue;
               $delayData->delay_data->delay_type = $delayType;
               $linkedList[] = json_encode($delayData);

           }

            $nextCard = $document->data->data->links->{$nextCard}->toOperator;



        }

        return $linkedList;

    }

    return 0;


}

