/*
Bootstable
 @description  Javascript library to make HMTL tables editable, using Bootstrap
 @version 1.1
 @autor Tito Hinostroza
*/
"use strict";
//Global variables
var params = null; //Parameters
var colsEdi = null;

var newColHtml =
  '<div class="btn-group pull-right">' +
  '<button id="bEdit" type="button" class="btn btn-sm btn-default"  onclick="rowEdit(this);">' +
  '<i class="fa fa-pencil-square-o"></i>' +
  "</button>" +
  '<button id="bElim" type="button" class="btn btn-sm btn-default"  onclick="rowElim(this);">' +
  '<i class="fa fa-trash" aria-hidden="true"></i>' +
  "</button>" +
  '<button id="bAcep" type="button" class="btn btn-sm btn-default"  style="display:none;" onclick="rowAcep(this);">' +
  '<i class="fa fa-check"></i>' +
  "</button>" +
  '<button id="bCanc" type="button" class="btn btn-sm btn-default" style="display:none;"  onclick="rowCancel(this);">' +
  '<i class="fa fa-times" aria-hidden="true"></i>' +
  "</button>" +
  "</div>";

var saveColHtml =
  '<div class="btn-group pull-right">' +
  '<button id="bEdit" type="button" class="btn btn-sm btn-default" style="display:none;" onclick="rowEdit(this);">' +
  '<i class="fa fa-pencil-square-o"></i>' +
  "</button>" +
  '<button id="bElim" type="button" class="btn btn-sm btn-default" style="display:none;" onclick="rowElim(this);">' +
  '<i class="fa fa-trash" aria-hidden="true"></i>' +
  "</button>" +
  '<button id="bAcep" type="button" class="btn btn-sm btn-default"   onclick="rowAcep(this);">' +
  '<i class="fa fa-check"></i>' +
  "</button>" +
  '<button id="bCanc" type="button" class="btn btn-sm btn-default"  onclick="rowCancel(this);">' +
  '<i class="fa fa-times" aria-hidden="true"></i>' +
  "</button>" +
  "</div>";
var colEdicHtml = '<td name="buttons">' + newColHtml + "</td>";
var colSaveHtml = '<td name="buttons">' + saveColHtml + "</td>";

$.fn.SetEditable = function(options) {
  var defaults = {
    columnsEd: null, //Index to editable columns. If null all td editables. Ex.: "1,2,3,4,5"
    $addButton: null, //Jquery object of "Add" button
    onEdit: function() {}, //Called after edit
    onBeforeDelete: function() {}, //Called before delete
    onDelete: function() {}, //Called after delete
    onAdd: function() {} //Called when added a new row
  };

  params = $.extend(defaults, options);
  this.find("thead tr").append('<th name="buttons"></th>'); //encabezado vacío
  this.find("tbody tr").append(colEdicHtml);
  var $tabedi = this; //Read reference to the current table, to resolve "this" here.

  //Process "addButton" parameter
  if (params.$addButton != null) {
    //Se proporcionó parámetro
    params.$addButton.click(function() {
      rowAddNew($tabedi.attr("id"));
    });
  }

  //Process "columnsEd" parameter
  if (params.columnsEd != null) {
    //Extract felds
    colsEdi = params.columnsEd.split(",");
  }
};

function IterarCamposEdit($cols, tarea) {
  // Look for the editable columns in the row
  let n = 0;
  $cols.each(function() {
    n++;
    if ($(this).attr("name") == "buttons") return; //excluye columna de botones
    if (!EsEditable(n - 1)) return; //noe s campo editable
    tarea($(this));
  });

  function EsEditable(idx) {
    // Indicates if the last column is set to be editable
    if (colsEdi == null) {
      //no se definió
      return true; //todas son editable
    } else {
      //hay filtro de campos
      //alert('verificando: ' + idx);
      for (var i = 0; i < colsEdi.length; i++) {
        if (idx == colsEdi[i]) return true;
      }
      return false; //no se encontró
    }
  }
}

function FijModoNormal(but) {
  $(but)
    .parent()
    .find("#bAcep")
    .hide();
  $(but)
    .parent()
    .find("#bCanc")
    .hide();
  $(but)
    .parent()
    .find("#bEdit")
    .show();
  $(but)
    .parent()
    .find("#bElim")
    .show();
  var $row = $(but).parents("tr"); //accede a la fila
  $row.attr("id", ""); // remove mark
}

function FijModoEdit(but) {
  $(but)
    .parent()
    .find("#bAcep")
    .show();
  $(but)
    .parent()
    .find("#bCanc")
    .show();
  $(but)
    .parent()
    .find("#bEdit")
    .hide();
  $(but)
    .parent()
    .find("#bElim")
    .hide();
  var $row = $(but).parents("tr"); //accede a la fila
  $row.attr("id", "editing"); //indica que está en edición
}

function ModoEdicion($row) {
  if ($row.attr("id") == "editing") {
    return true;
  } else {
    return false;
  }
}

function rowAcep(but) {
  // Accept changes from the edit

  var $row = $(but).parents("tr"); //accede a la fila
  var $cols = $row.find("td"); //lee campos

  if (!ModoEdicion($row)) return; // It is already in edit

  // It's an edition. The edit must be finalized
  IterarCamposEdit($cols, function($td) {
    //itera por la columnas
    let cont = $td.find("input").val(); //lee contenido del input
    $td.html(cont); //fija contenido y elimina controles
  });

  FijModoNormal(but);
  params.onEdit($row); // pass the row to the onEdit function defined in SetEditable for the table
}

function rowCancel(but) {
  //Rechaza los cambios de la edición
  var $row = $(but).parents("tr"); //accede a la fila
  var $cols = $row.find("td"); //lee campos
  if (!ModoEdicion($row)) return; //Ya está en edición
  //Está en edición. Hay que finalizar la edición
  IterarCamposEdit($cols, function($td) {
    //itera por la columnas
    var cont = $td.find("div").html(); //lee contenido del div
    $td.html(cont); //fija contenido y elimina controles
  });
  FijModoNormal(but);
}

function rowEdit(but) {
  var $td = $("tr[id='editing'] td");
  rowAcep($td);

  var $row = $(but).parents("tr");
  var $cols = $row.find("td");

  if (ModoEdicion($row)) return; // It is already in edit mode so return
  // Put in edit mode
  IterarCamposEdit($cols, function($td) {
    // iterate through the columns
    var cont = $td.html(); // read content
    var div = '<div style="display: none;">' + cont + "</div>"; // save existing content in none display div
    var input = '<input class="form-control input-sm" value="' + cont + '">';
    $td.html(div + input); // put div and input field into the table td
  });
  FijModoEdit(but);
}

function rowElim(but) {
  // Delete the current row
  const $row = $(but).parents("tr"); // find the row
  params.onBeforeDelete($row);
  $row.remove();
  params.onDelete($row);
}

function rowAddNew(tabId) {
  // Add a row to the indicated table.
  var $tab_en_edic = $("#" + tabId); //Table to edit
  var $filas = $tab_en_edic.find("tbody tr");
  var $cols;

  if ($filas.length == 0) {
    // There are no rows of data. You have to create them complete
    var $row = $tab_en_edic.find("thead tr"); // header
    $cols = $row.find("th"); // read fields

    //construye html
    var htmlDat = "";
    $cols.each(function() {
      if ($(this).attr("name") === "buttons") {
        // It is a column of buttons
        htmlDat = htmlDat + colEdicHtml; // add buttons
      } else {
        htmlDat = htmlDat + "<td></td>";
      }
    });
    $tab_en_edic.find("tbody").append("<tr>" + htmlDat + "</tr>");
  } else {
    // There are other rows, we can clone the last row, to copy the buttons
    var $ultFila = $tab_en_edic.find("tr:last");
    $ultFila.clone().appendTo($ultFila.parent());
    $tab_en_edic.find("tr:last").attr("id", "editing");
    $ultFila = $tab_en_edic.find("tr:last");
    $cols = $ultFila.find("td"); //lee campos

    $cols.each(function() {
      if ($(this).attr("name") == "buttons") {
        //Es columna de botones
      } else {
        var div = '<div style="display: none;"></div>'; // save content
        var input = '<input class="form-control input-sm" value="">';

        $(this).html(div + input); // clean content
      }
    });
    $ultFila.find("td:last").html(saveColHtml);
  }
  params.onAdd();
}

function TableToCSV(tabId, separator) {
  //Convert tabla a CSV
  var datFil = "";
  var tmp = "";
  var $tab_en_edic = $("#" + tabId); //Table source

  $tab_en_edic.find("tbody tr").each(function() {
    // Finish the edition if it exists
    if (ModoEdicion($(this))) {
      $(this)
        .find("#bAcep")
        .click(); //acepta edición
    }

    var $cols = $(this).find("td"); //lee campos
    datFil = "";

    $cols.each(function() {
      if ($(this).attr("name") === "buttons") {
        //Es columna de botones
      } else {
        datFil = datFil + $(this).html() + separator;
      }
    });
    if (datFil !== "") {
      datFil = datFil.substr(0, datFil.length - separator.length);
    }
    tmp = tmp + datFil + "\n";
  });
  return tmp;
}
