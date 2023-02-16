import 'semantic-ui-css/components/accordion';
import 'dropzone/dist/dropzone.css';
import $ from 'jquery';
import 'jquery.dirtyforms/jquery.dirtyforms';

import 'sylius/ui/app';
import 'sylius/ui/sylius-auto-complete';
import 'sylius/ui/sylius-product-attributes';
import 'sylius/ui/sylius-product-auto-complete';

import './sylius-compound-form-errors';
import './sylius-menu-search';
import './sylius-product-images-preview';

//owl
import './owl-permission';
import './owl-notification';
import './owl-equipment';

import './jquery.form';
import './owl-form';
import './owl-modal-ajax';
import GridComponent from 'sylius/ui/owl-grid';

$(window).off('beforeunload');

import * as Turbo from '@hotwired/turbo';
 
var isLoadPage = false;

Turbo.session.adapter.__proto__.visitRequestCompleted = function(visit) {
  
  function isSuccessful(statusCode) {
    return statusCode >= 200 && statusCode < 300;
  }

  if (visit.response) {
    const { statusCode, responseHTML } = visit.response
    const snapshot = Turbo.PageSnapshot.fromHTMLString(responseHTML);
    visit.render(async () => {
      if (visit.view.renderPromise) await visit.view.renderPromise
      if (isSuccessful(statusCode) && responseHTML != null) {
        await visit.view.renderPage(snapshot, false, visit.willRender)
        visit.adapter.visitRendered(visit)
        visit.complete()
      } else {
        await visit.view.renderError(snapshot)
        visit.adapter.visitRendered(visit)
        visit.fail()
      }

      visit.view.snapshotCache.put(new URL(visit.location.href), snapshot)
    })
  }
}

document.addEventListener('turbo:before-cache',  (event) => {
  event.preventDefault();
  let checkboxAll = $('[data-js-bulk-checkboxes');

  if(checkboxAll.is(':checked')) {
    checkboxAll.trigger('click');
  }

  $('.modals').remove();
  $('body').removeClass('dimmable scrolling')
});

document.addEventListener('turbo:render',  (event) => {  
  if ($('.sylius-grid-wrapper table').length) {
    const grid = new GridComponent(document.querySelector('.sylius-grid-wrapper'));

    if(!isLoadPage) {
      grid.removeLoading();
    }else{
      grid.addLoading();
    }
  }
}) 

document.addEventListener('turbo:before-fetch-request',  (event) => {
  isLoadPage = true;

  if ($('.sylius-grid-wrapper table').length) {
    const grid = new GridComponent(document.querySelector('.sylius-grid-wrapper'));

    grid.addLoading();
  }
})

document.addEventListener('turbo:before-fetch-response', (event) => {
  isLoadPage= false; 

  if ($('.sylius-grid-wrapper table').length) {
    const grid = new GridComponent(document.querySelector('.sylius-grid-wrapper'));

    grid.removeLoading();
  }
})

document.addEventListener("turbo:load", () => {

  if($('body').find('.tinymce').length) {
    initTinyMCE();
  }

  // permission
  $('.ui.checkbox-permission').changePermission();

  $('.sylius-autocomplete').autoComplete();

  $('.product-select.ui.fluid.multiple.search.selection.dropdown').productAutoComplete();
  $('div#attributeChoice > .ui.dropdown.search').productAttributes();
  $('form[name=owl_equipment]').equipment();

  $('table thead th.sortable').on('click', (event) => {
    window.location = $(event.currentTarget).find('a').attr('href');
  });

  $('form.is-ajax').ajaxForm();
  $('.ajax-modal-button').each((index, element) => {
    if ($._data($(element).get(0), 'events') == undefined) {
      $(element).ajaxModal();
    }
  })

  $('#actions a[data-form-collection="add"]').on('click', () => {
    setTimeout(() => {
      $('select[name^="sylius_promotion[actions]"][name$="[type]"]').last().change();
    }, 50);
  });
  $('#rules a[data-form-collection="add"]').on('click', (event) => {
    const name = $(event.target).closest('form').attr('name');

    setTimeout(() => {
      $(`select[name^="${name}[rules]"][name$="[type]"]`).last().change();
    }, 50);
  });

  $('body').on('click', '.table-location-equipments .delete', function() {
    $(this).parents('tr').remove();
  })

  $(document).on('change-autocomplete-product', (event, id, data ) => {
    let $search = $(event.target.activeElement).parents('.sylius-autocomplete');

    if(!$('#owl_equipment_equipments_'+id).length) {
      $('.table-location-equipments').prepend(
        '<tr class="green">'+
            '<td>'
              +data.name+
              '<input type="hidden" id="owl_equipment_equipments_'+id+'" name="owl_equipment[equipments]['+id+']" value="'+id+'">'+
            '</td>'+
            '<td>'+(data.symbol !== undefined ? data.symbol : '')+'</td>'+
            '<td>'+
              '<button class="ui red labeled icon button mini delete" type="submit">'+
                '<i class="icon trash"></i> Usuń'+
              '</button>'+
            '</td>'+
        '</tr>'
      );
    }

    $search.dropdown('clear');
    $search.dropdown('set text', data.name);
  });

  $(document).on('collection-form-add', () => {
    $('.sylius-autocomplete').each((index, element) => {
      if ($._data($(element).get(0), 'events') == undefined) {
        $(element).autoComplete();
      }
    });
  });
  $(document).on('collection-form-update', () => {
    $('.sylius-autocomplete').each((index, element) => {
      if ($._data($(element).get(0), 'events') == undefined) {
        $(element).autoComplete();
      }
    });
  });

  $('.sylius-tabular-form').addTabErrors();
  $('.ui.accordion').addAccordionErrors();

  $(document).previewUploadedImage('#sylius_product_images');
  $(document).previewUploadedImage('#sylius_taxon_images');

  $(document).previewUploadedImage('#add-avatar');

  $('body').on('DOMNodeInserted', '[data-form-collection="item"]', (event) => {
    if ($(event.target).find('.ui.accordion').length > 0) {
      $(event.target).find('.ui.accordion').accordion();
    }
  });

  $('#more-details').accordion({ exclusive: false });

  $('.sylius-admin-menu').searchable('.sylius-admin-menu-search-input');

  $('.accept-notification').acceptNotification();
});

window.$ = $;
window.jQuery = $;
