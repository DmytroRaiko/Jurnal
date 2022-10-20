document.addEventListener('DOMContentLoaded', () => {
   'use strict';

   let cords = ['scrollX','scrollY'];
   window.addEventListener('unload', e => {
      cords.forEach(cord => {localStorage[cord] = window[cord];});
   });
   window.scroll(...cords.map(cord => localStorage[cord]));
   
   const addGroupTeacher = document.getElementById('add-group-teacher'),
      filterTableTeacher = document.getElementById('filter-table-teacher'),
      addPairTeacher = document.getElementById('add-pair-teacher');

   addGroupTeacher.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order');

      if (target.closest('.x') || (target === modal)) {
         addGroupTeacher.style.display = 'none';
         filterTableTeacher.style.display = 'none';
         addPairTeacher.style.display = 'none';
      }
   });

   filterTableTeacher.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order-filter-table-teacher');

      if (target.closest('.x-filter-table-teacher') || (target === modal)) {
         addGroupTeacher.style.display = 'none';
         filterTableTeacher.style.display = 'none';
         addPairTeacher.style.display = 'none';
      }
   });
   
   addPairTeacher.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order-add-pair-teacher');

      if (target.closest('.x-add-pair-teacher') || (target === modal)) {
         addGroupTeacher.style.display = 'none';
         filterTableTeacher.style.display = 'none';
         addPairTeacher.style.display = 'none';
      }
   });


   const zvit3 = document.getElementById('zvit-teacher');

   zvit3.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order-zvit-teacher');
   
      if (target.closest('.x-zvit-teacher') || (target === modal)) {
         zvit3.style.display = 'none';
      }
   });
   

   const backToSubject = document.getElementById('back-to-subject'),
      backToGroup  = document.getElementById('back-to-group'),
      blockSubject = document.getElementById('block-subject'),
      blockGroup = document.getElementById('block-group'),
      blockTable = document.getElementById('block-table-teacher'),
      backZone = document.getElementById('back-zone'),
      filter = document.getElementById('filter');

   backToSubject.addEventListener('click', () => {

      backToGroup.style.display = "none";
      blockGroup.style.display = "none";
      blockTable.style.display = "none";
      blockSubject.style.display = "flex";
      backToSubject.style.display = "none";
      backZone.style.width = "100%";
      filter.style.display = "none";
   });

   backToGroup.addEventListener('click', () => {

      backToGroup.style.display = "none";
      blockGroup.style.display = "flex";
      blockTable.style.display = "none";
      blockSubject.style.display = "none";
      backZone.style.width = "100%";
      filter.style.display = "none";
   });
   
   window.addEventListener("load", () => {

      const addPair = document.getElementById("pair-date");
      let s = new Date(),
         year = s.getFullYear(),
         month = s.getMonth()+1,
         day = s.getDate();

      if (day < 10) {
         day = "0"+day;
      }
      if (month < 10) {
         month = "0"+month;
      }

      let today = year+"-"+month+"-"+day;
      
      addPair.setAttribute("max", today);
   });
});