document.addEventListener('DOMContentLoaded', () => {
   'use strict';

   let cords = ['scrollX','scrollY'];
   window.addEventListener('unload', e => {
      cords.forEach(cord => {localStorage[cord] = window[cord];});
   });
   window.scroll(...cords.map(cord => localStorage[cord]));

   const jurnal = document.getElementById('jurnal'),
      jurnalZone = document.getElementById('jurnal-zone'),
      visit = document.getElementById('visit'),
      visitZone = document.getElementById('visit-zone'),
      topJurnal = document.querySelector('.jurnal'),
      topVisit = document.querySelector('.visit'),
      topMenu = document.getElementById('top-menu'),
      burger = document.querySelector('.burger'), 
      backToSubject = document.getElementById('back-to-subject');
      
   let counter = 0,
      block = -1;   

   const filterTableStudent = document.getElementById('filter-table-student');

   filterTableStudent.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order-filter-table-student');

      if (target.closest('.x-filter-table-student') || (target === modal)) {

         filterTableStudent.style.display = 'none';
         filterTableVisit.style.display = 'none';
      }
   });
   const filterTableVisit = document.getElementById('filter-table-visit');

   filterTableVisit.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order-filter-table-visit');
   
      if (target.closest('.x-filter-table-visit') || (target === modal)) {
         filterTableVisit.style.display = 'none';
         filterTableStudent.style.display = 'none';
      }
   });

   jurnal.addEventListener('click', () => {

      jurnalZone.style.display = 'flex'; 
      visitZone.style.display = 'none'; 
   });

   visit.addEventListener('click', () => {

      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'flex'; 
   });

   backToSubject.addEventListener('click', () => {

      
   });

   topJurnal.addEventListener('click', () => {

      jurnalZone.style.display = 'flex'; 
      visitZone.style.display = 'none'; 
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });
   
   topVisit.addEventListener('click', () => {

      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'flex';  
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });


   burger.addEventListener('click', () => {
      
      if (counter === 0) {
         topMenu.style.maxHeight = '500px';
         topMenu.style.fontSize = '20px';
         counter = 1;
      } else if (counter === 1) {
         topMenu.style.maxHeight = '0px';
         topMenu.style.fontSize = '0px';
         counter = 0;
      }
   });


});