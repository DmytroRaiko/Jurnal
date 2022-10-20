document.addEventListener('DOMContentLoaded', () => {
   'use strict';

   let cords = ['scrollX','scrollY'];
   window.addEventListener('unload', e => {
      cords.forEach(cord => {localStorage[cord] = window[cord];});
   });
   window.scroll(...cords.map(cord => localStorage[cord]));

   const user = document.getElementById('user'),
      userZone = document.getElementById('user-zone'),
      dbStudent = document.getElementById('db-student'),
      dbStudentZone = document.getElementById('db-student-zone'),
      dbTeacher = document.getElementById('db-teacher'),
      dbTeacherZone = document.getElementById('db-teacher-zone'),
      blockControl = document.getElementById('block-control'),
      blockControlZone = document.getElementById('block-control-zone'),
      jurnal = document.getElementById('jurnal'),
      jurnalZone = document.getElementById('jurnal-zone'),
      visit = document.getElementById('visit'),
      visitZone = document.getElementById('visit-zone'),
      topUser = document.querySelector('.user'),
      topDbStudent = document.querySelector('.db-student'),
      topDbTeacher = document.querySelector('.db-teacher'),
      topBlockControl = document.querySelector('.block-control'),
      topJurnal = document.querySelector('.jurnal'),
      topVisit = document.querySelector('.visit'),
      topMenu = document.getElementById('top-menu'),
      burger = document.querySelector('.burger');

   let counter = 0,
      block = -1;

   
   window.addEventListener('load', event => {
      event.preventDefault();

      /*userZone.style.display = 'none'; 
      dbStudentZone.style.display = 'none'; 
      dbTeacherZone.style.display = 'none'; 
      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 
*/
      if (block === 1) {         
         userZone.style.display = 'flex'; 
      } else if (block ===2) {
         dbStudentZone.style.display = 'flex'; 
      }
   });
   
   user.addEventListener('click', () => {

      userZone.style.display = 'flex'; 
      dbStudentZone.style.display = 'none'; 
      dbTeacherZone.style.display = 'none'; 
      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 

      block = 1;
      console.log('block: ', block);
   });

   dbStudent.addEventListener('click', () => {

      userZone.style.display = 'none'; 
      dbStudentZone.style.display = 'flex'; 
      dbTeacherZone.style.display = 'none'; 
      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 

      block = 2;
      console.log('block: ', block);
   });
   
   dbTeacher.addEventListener('click', () => {

      userZone.style.display = 'none'; 
      dbStudentZone.style.display = 'none'; 
      dbTeacherZone.style.display = 'flex'; 
      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 

      block = 3;
      console.log('block: ', block);
   });
   
   blockControl.addEventListener('click', () => {

      userZone.style.display = 'none'; 
      dbStudentZone.style.display = 'none'; 
      dbTeacherZone.style.display = 'none'; 
      blockControlZone.style.display = 'flex'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 
   });
   
   jurnal.addEventListener('click', () => {

      userZone.style.display = 'none'; 
      dbStudentZone.style.display = 'none'; 
      dbTeacherZone.style.display = 'none'; 
      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'flex'; 
      visitZone.style.display = 'none'; 
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

   visit.addEventListener('click', () => {

      userZone.style.display = 'none'; 
      dbStudentZone.style.display = 'none'; 
      dbTeacherZone.style.display = 'none'; 
      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'flex'; 
   });

   topUser.addEventListener('click', () => {

      userZone.style.display = 'flex'; 
      dbStudentZone.style.display = 'none'; 
      dbTeacherZone.style.display = 'none'; 
      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });

   topDbStudent.addEventListener('click', () => {

      userZone.style.display = 'none'; 
      dbStudentZone.style.display = 'flex'; 
      dbTeacherZone.style.display = 'none'; 
      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });
   
   topDbTeacher.addEventListener('click', () => {

      userZone.style.display = 'none'; 
      dbStudentZone.style.display = 'none'; 
      dbTeacherZone.style.display = 'flex'; 
      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });
   
   topBlockControl.addEventListener('click', () => {

      userZone.style.display = 'none'; 
      dbStudentZone.style.display = 'none'; 
      dbTeacherZone.style.display = 'none'; 
      blockControlZone.style.display = 'flex'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'none'; 
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });
   
   topJurnal.addEventListener('click', () => {

      userZone.style.display = 'none'; 
      dbStudentZone.style.display = 'none'; 
      dbTeacherZone.style.display = 'none'; 
      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'flex'; 
      visitZone.style.display = 'none'; 
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });
   
   topVisit.addEventListener('click', () => {

      userZone.style.display = 'none'; 
      dbStudentZone.style.display = 'none'; 
      dbTeacherZone.style.display = 'none'; 
      blockControlZone.style.display = 'none'; 
      jurnalZone.style.display = 'none'; 
      visitZone.style.display = 'flex'; 
      topMenu.style.maxHeight = '0px';
      topMenu.style.fontSize = '0px';
      counter = 0;
   });
});

