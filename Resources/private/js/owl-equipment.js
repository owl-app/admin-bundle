import 'semantic-ui-css/components/dropdown';
import $ from 'jquery';

const equipmentCategoryTransportId = 17;

const setChangeCategoryListener = function setChangeCategoryListener(evt) {
  const $categorySelect = this.find('#owl_equipment_category'),
        $menuRefueling = this.find('.menu [data-tab="refuelings"]'),
        $tabRefuelingCollection = this.find('.tab[data-tab="refuelings"] [data-form-collection="list"]');

  $($categorySelect).on('equipment-change-category', (event, categoryId) => {
    if(parseInt(categoryId) !== equipmentCategoryTransportId) {
      $menuRefueling.css('display', 'none');
      $tabRefuelingCollection.html('');
    }else{
      $menuRefueling.css('display', 'block');
    }
  });
}

$.fn.extend({
  equipment() {
    setChangeCategoryListener.bind(this)();
  },
});
