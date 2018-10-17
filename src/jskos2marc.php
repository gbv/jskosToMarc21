<?php

namespace JSKOS;

// 1. JSKOS too MARC JSON :

// http://format.gbv.de/marc/json
// https://github.com/scriptotek/mc2skos#mapping-schema-for-marc21-authority
// https://www.loc.gov/marc/authority/

function jskos2marcxml($jskoses) {
  $marc21Records = [];
  
  foreach($jskoses as $jskos) {
      $marc = [];

      // LEADER
      $marc[] = [
          'LDR', '', '', '', '00000nz  a2200000nc 4500'
      ];

      // modified  
      if (isset($jskos['modified'])) {
          $modifiedDate = $jskos['modified'];
          $modifiedDate = strtotime($modifiedDate);
          $modifiedDate = date('YmdHis.0', $modifiedDate);
          $marc[] = [
              '005', '', '', '', $modifiedDate
          ];
      }

      // uri
      if (isset($jskos['uri'])) {
          $marc[] = [
              '024', '7', '', 'a', $jskos['uri'],
              '2', 'uri'
          ];
      }  
      
      // identifier
      if (isset($jskos['identifier'])) {
          $identifiers = $jskos['identifier'];
          foreach ($identifiers as $identifier) {
            $marc[] = [
                '035', '', '', 'a', $identifier
            ];
          }
      }  
      
      // notation
      if (isset($jskos['notation'])) {
          $notations = $jskos['notation'];
          foreach ($notations as $notation) {
            $marc[] = [
                '035', '', '', 'a', $notation
            ];
          }
      }  

      // prefLabel
      if (isset($jskos['prefLabel'])) {
          $prefLabels = $jskos['prefLabel'];
          $marc[] = [
              '100', '1', '', 'a', $prefLabels['de']
          ];
      }
      // altLabel
      if (isset($jskos['altLabel'])) {
          $altLabels = $jskos['altLabel'];
          foreach ($altLabels as $altLabelLang) {
            foreach ($altLabelLang as $entry) {
              $marc[] = [
                  '400', '1', '', 'a', $entry
              ];
            }
          }
      }
      // hiddenLabel
      if (isset($jskos['hiddenLabel'])) {
          $hiddenLabels = $jskos['hiddenLabel'];
          foreach ($hiddenLabels as $hiddenLabelLang) {
            foreach ($hiddenLabelLang as $entry) {
              $marc[] = [
                  '400', '1', '', 'a', $entry
              ];
            }
          }
      }
      // editorialNote
      if (isset($jskos['editorialNote'])) {
          $editorialNotes = $jskos['editorialNote'];
          foreach ($editorialNotes as $editorialNoteLang) {
            foreach ($editorialNoteLang as $entry) {
              $marc[] = [
                  '667', '', '', 'a', $entry
              ];
            }
          }
      }
      // definition
      if (isset($jskos['definition'])) {
          $definitions = $jskos['definition'];
          foreach ($definitions as $definitionLang) {
            foreach ($definitionLang as $entry) {
              $marc[] = [
                  '677', '', '', 'a', $entry
              ];
            }
          }
      }
      // note
      if (isset($jskos['note'])) {
          $notes = $jskos['note'];
          foreach ($notes as $noteLang) {
            foreach ($noteLang as $entry) {
              $marc[] = [
                  '680', '', '', 'a', $entry
              ];
            }
          }
      }
      // example
      if (isset($jskos['example'])) {
          $examples = $jskos['example'];
          foreach ($examples as $exampleLang) {
            foreach ($exampleLang as $entry) {
              $marc[] = [
                  '681', '', '', 'a', $entry
              ];
            }
          }
      }
      // changeNote
      if (isset($jskos['changeNote'])) {
          $changeNotes = $jskos['changeNote'];
          foreach ($changeNotes as $changeNoteLang) {
            foreach ($changeNoteLang as $entry) {
              $marc[] = [
                  '682', '', '', 'a', $entry
              ];
            }
          }
      }
      // changeNote
      if (isset($jskos['historyNote'])) {
          $historyNotes = $jskos['historyNote'];
          foreach ($historyNotes as $historyNoteLang) {
            foreach ($historyNoteLang as $entry) {
              $marc[] = [
                  '688', '', '', 'a', $entry
              ];
            }
          }
      }
      
      // startDate + endDate
      $startDate = '';
      if(isset($jskos['startDate'])) {
        $startDate = $jskos['startDate'];
      }
      $endDate = '';
      if(isset($jskos['endDate'])) {
        $endDate = $jskos['endDate'];
      }
      if ($startDate != '' || $endDate != '') {
        $dateStr = $startDate . '-' . $endDate;
        $type = 'http://d-nb.info/standards/elementset/gnd#dateOfBirthAndDeath';
        if($endDate == '') {
          $type = 'http://d-nb.info/standards/elementset/gnd#dateOfBirth';
        }
        if($startDate == '') {
          $type = 'http://d-nb.info/standards/elementset/gnd#dateOfDeath';
        }
        $marc[] = [
            '548', '', '', 'a', $dateStr,
            '4', 'datl',
            '4', $type,
            'w', 'r',
            'i', 'Lebensdaten', 
        ];
      }
      
      // startPlace + endPlace --> not in jskos yet
      
      /*
          <datafield tag="551" ind1=" " ind2=" ">
            <subfield code="0">(DE-101)040427420</subfield>
            <subfield code="0">(DE-588)4042742-0</subfield>
            <subfield code="0">http://d-nb.info/gnd/4042742-0</subfield>
            <subfield code="a">Nürnberg</subfield>
            <subfield code="4">ortg</subfield>
            <subfield code="4">http://d-nb.info/standards/elementset/gnd#placeOfBirth</subfield>
            <subfield code="w">r</subfield>
            <subfield code="i">Geburtsort</subfield>
          </datafield>
          <datafield tag="551" ind1=" " ind2=" ">
            <subfield code="0">(DE-101)040427420</subfield>
            <subfield code="0">(DE-588)4042742-0</subfield>
            <subfield code="0">http://d-nb.info/gnd/4042742-0</subfield>
            <subfield code="a">Nürnberg</subfield>
            <subfield code="4">orts</subfield>
            <subfield code="4">http://d-nb.info/standards/elementset/gnd#placeOfDeath</subfield>
            <subfield code="w">r</subfield>
            <subfield code="i">Sterbeort</subfield>
          </datafield>
      */
      
      // related
      // broder
      // narrower
      
      // literature (GVK, free literaturetext...?) --> not in jskos yet

      
      $marcXML = marcjson2marcxml($marc);
      array_push($marc21Records, $marcXML);
  }
  if(count($marc21Records) > 1) {
    $marc21Records = '<records>' . implode(PHP_EOL, $marc21Records) . '</records>';
  }
  else {
    $marc21Records = implode(PHP_EOL, $marc21Records);
  }
  $dom = new \DOMDocument;
  $dom->preserveWhiteSpace = FALSE;
  $dom->loadXML($marc21Records);
  $dom->formatOutput = TRUE;
  $marcXML = $dom->saveXML();

  return $marcXML;
}

// Zum testen des umgekehrten weges:
//
// $ wget -O gnd.xml https://d-nb.info/11572835X/about/marcxml
// $ mc2skos -o jskos --scheme gnd marcxml

// 2. MARC-JSON too MARC-XML
function marcjson2marcxml($marc) {
  $xml = "<record xmlns=\"http://www.loc.gov/MARC21/slim\" type=\"Authority\">\n";
  foreach ($marc as $field) {
    if ($field[0] == 'LDR') {
      $xml .= "<leader>".$field[4]."</leader>\n";
    } 
    else if ($field[0] < 10) {
      $xml .= "<controlfield tag=\"$field[0]\">$field[4]</controlfield>\n";
    } 
    else {
      $xml .= '<datafield tag="' . $field[0] . '" ind1="' . $field[1] . '" ind2="' . $field[2] . '">' . PHP_EOL;
      for ($i=3; $i<count($field); $i+=2) {
        $xml .= '<subfield code="' . $field[$i] . '">';
        $xml .= htmlspecialchars($field[$i+1]);
        $xml .= "</subfield>\n";
      }
      $xml .= "</datafield>\n";
    }
  }
  $xml .= "</record>\n";
  return $xml;
}

function jskos_decode($json) {
    $jskos = json_decode($json, TRUE);
    return preg_match('/^\s*{/', $json) ? [ $jskos ] : $jskos;
}

?>
