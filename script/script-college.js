document.addEventListener('DOMContentLoaded', () => {
   'use strict';

   let cords = ['scrollX','scrollY'];
   window.addEventListener('unload', e => {
      cords.forEach(cord => {localStorage[cord] = window[cord];});
   });
   window.scroll(...cords.map(cord => localStorage[cord]));

   const blockControl = document.getElementById('block-control'),
      blockControlZone = document.getElementById('block-control-zone'),
      jurnal = document.getElementById('jurnal'),
      jurnalZone = document.getElementById('jurnal-zone'),
      visit = document.getElementById('visit'),
      visitZone = document.getElementById('visit-zone'),
      topBlockControl = document.querySelector('.block-control'),
      topJurnal = document.querySelector('.jurnal'),
      topVisit = document.querySelector('.visit'),
      topMenu = document.getElementById('top-menu'),
      burger = document.querySelector('.burger');

   let counter = 0,
      block = -1;

   
   window.addEventListener('load', event => {
      event.preventDefault();

      if (block === 1) {         
         userZone.style.display = 'flex'; 
      } else if (block ===2) {
         dbStudentZone.style.display = 'flex'; 
      }
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

   blockControl.addEventListener('click', () => {

      blockControlZone.style.display = 'flex'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 
   });
   
   jurnal.addEventListener('click', () => {

      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'flex'; 
      visitZone.style.display = 'none'; 
   });

   visit.addEventListener('click', () => {

      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'flex'; 
   });
   
   topBlockControl.addEventListener('click', () => {

      blockControlZone.style.display = 'flex'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });
   
   topJurnal.addEventListener('click', () => {

      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'flex'; 
      visitZone.style.display = 'none'; 
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });
   
   topVisit.addEventListener('click', () => {

      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'flex'; 
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });

   const filterMarkAdmin = document.getElementById('filter-table-mark-admin'),
      felterVisitAdmin = document.getElementById('filter-table-visit');

   filterMarkAdmin.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order-filter-table-mark-admin');
   
      if (target.closest('.x-filter-table-mark-admin') || (target === modal)) {
         filterMarkAdmin.style.display = 'none';
      }
   });

   felterVisitAdmin.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order-filter-table-visit');
   
      if (target.closest('.x-filter-table-visit') || (target === modal)) {
         felterVisitAdmin.style.display = 'none';
      }
   });
});

