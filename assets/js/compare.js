const $ = require('jquery');
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

var pokemons = [];
var learnMethod = '';
var generation = [];
var pokemonIndex;
var generationIndex;

var maxPokemon;
var maxGeneration;

const compareDiv = document.getElementById('compare-div');
const configuration = {
    drawFileList: false,
    matching: 'lines',
    fileListToggle: false,
    fileListStartVisible: false,
    fileContentToggle: false,
    outputFormat: 'side-by-side',
};

$('#initCompare').click(function () {

    var startAt = $('#startAtCompare').val();
    $.ajax
    ({
        url: Routing.generate('_init_compare'),
        data: {"startAt": startAt},
        type: 'post',
        success: function (result) {
            pokemons = result.data.pokemons;
            learnMethod = result.data.learnMethod;
            generation = result.data.generations;
            $('#start-div').hide();
            $('#next-compare').show();
            pokemonIndex = 0;
            generationIndex = 0;
            maxPokemon = pokemons.length;
            maxGeneration = generation.length;
        },
        error: function (error) {
            $('#logs-compare').show();
            $('#logs-compare').text(error.error);
        }
    });
});

$('#next-compare').click(function () {

    $('#next-compare').hide();

    processDiff();
});

function processDiff() {

    $('#generated-moves').empty();
    $('#compare-div').empty();
    $('#upload-changes').hide();

    $.ajax
    ({
        url: Routing.generate('_next_compare'),
        data: {
            "pokemon": pokemons[pokemonIndex],
            "generation": generation[generationIndex],
            "learnMethod": learnMethod
        },
        type: 'post',
        success: function (result) {


            if (generationIndex === maxGeneration - 1) {
                generationIndex = 0;
                pokemonIndex++;
            } else {
                generationIndex++;
            }

            if (!result.data.available || !result.data.diff) {
                $('#logs-compare').text(result.data.text);
                processDiff();

                return;
            }

            let diffString = result.data.diffString;
            let diff2htmlUi = new Diff2HtmlUI(compareDiv, diffString, configuration);
            try {
                diff2htmlUi.draw();
            } catch (error) {
            }
            $('#generated-moves').html(result.data.generated)
            $('#logs-compare').text('Difference found, fix it or skip');
            $('#next-compare').show();
            $('#upload-changes').show();

        },
        error: function (result) {
            $('#logs-compare').text(result.error);
        },
        complete: function (result, status) {
        }
    });
}

