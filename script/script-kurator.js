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
      myGroup = document.getElementById('my-group'),
      jurnalMyGroupZone = document.getElementById('jurnal-my-group-zone'),
      topJurnal = document.querySelector('.jurnal'),
      topVisit = document.querySelector('.visit'),
      topMyGroup = document.querySelector('.my-group'),
      topMenu = document.getElementById('top-menu'),
      burger = document.querySelector('.burger');
      
   let counter = 0,
      block = -1;   

   const filterTableVisit = document.getElementById('filter-table-visit'),
      addDayKurator = document.getElementById('add-day-kurator'),
      filterTableMyGroup = document.getElementById('filter-table-my-group');

   const backToSubject = document.getElementById('back-to-subject-kurator'),
      blockSubject = document.getElementById('block-subject-kurator'),
      blockTable = document.getElementById('block-table-my-group'),
      backZone = document.getElementById('back-to-subject-kurator'),
      filter = document.getElementById('filter-kurator');

   backToSubject.addEventListener('click', () => {

      blockTable.style.display = "none";
      blockSubject.style.display = "flex";
      filter.style.display = "none";
      backZone.style.display = "none";
   });


   filterTableVisit.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order-filter-table-visit');
   
      if (target.closest('.x-filter-table-visit') || (target === modal)) {
         filterTableVisit.style.display = 'none';
      }
   });

   addDayKurator.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order-add-day-kurator');
   
      if (target.closest('.x-add-day-visit') || (target === modal)) {
         addDayKurator.style.display = 'none';
      }
   });

   filterTableMyGroup.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order-filter-table-my-group');
   
      if (target.closest('.x-filter-table-my-group') || (target === modal)) {
         filterTableMyGroup.style.display = 'none';
      }
   });

   jurnal.addEventListener('click', () => {

      jurnalZone.style.display = 'flex'; 
      visitZone.style.display = 'none'; 
      jurnalMyGroupZone.style.display = 'none';
   });

   visit.addEventListener('click', () => {

      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'flex'; 
      jurnalMyGroupZone.style.display = 'none';
   });

   myGroup.addEventListener('click', () => {

      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 
      jurnalMyGroupZone.style.display = 'flex';
   });

   topJurnal.addEventListener('click', () => {

      jurnalZone.style.display = 'flex'; 
      visitZone.style.display = 'none'; 
      jurnalMyGroupZone.style.display = 'none';
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });
   
   topVisit.addEventListener('click', () => {

      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'flex'; 
      jurnalMyGroupZone.style.display = 'none';  
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });

   topMyGroup.addEventListener('click', () => {

      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 
      jurnalMyGroupZone.style.display = 'flex';
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