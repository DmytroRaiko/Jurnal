document.addEventListener('DOMContentLoaded', () => {
   'use strict';

   const user = document.getElementById('user'),
      dbStudent = document.getElementById('db-student'),
      dbTeacher = document.getElementById('db-teacher'),
      blockControl = document.getElementById('block-control'),
      jurnal = document.getElementById('jurnal'),
      visit = document.getElementById('visit'),
      topUser = document.querySelector('.user'),
      topDbStudent = document.querySelector('.db-student'),
      topDbTeacher = document.querySelector('.db-teacher'),
      topBlockControl = document.querySelector('.block-control'),
      topJurnal = document.querySelector('.jurnal'),
      topVisit = document.querySelector('.visit');

   user.addEventListener('click', () => {

      user.style.background = 'rgb(51, 76, 155)';
      user.style.color = 'white';
      user.style.fontWeight = '600';
      user.style.boxShadow = '4px 4px 5px 4px rgba(58, 127, 231, 0.7)';
      topUser.style.background = 'rgb(8, 46, 170)';
      topUser.style.color = 'white';
     
      dbStudent.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbStudent.style.color = 'rgb(0, 0, 0)';
      dbStudent.style.fontWeight = '500';
      dbStudent.style.boxShadow = 'none';
      topDbStudent.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbStudent.style.color = 'rgb(8, 46, 170)';

      dbTeacher.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbTeacher.style.color = 'rgb(0, 0, 0)';
      dbTeacher.style.fontWeight = '500';
      dbTeacher.style.boxShadow = 'none';
      topDbTeacher.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbTeacher.style.color = 'rgb(8, 46, 170)';

      blockControl.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      blockControl.style.color = 'rgb(0, 0, 0)';
      blockControl.style.fontWeight = '500';
      blockControl.style.boxShadow = 'none';
      topBlockControl.style.backgroundColor = 'rgb(49, 173, 211)';
      topBlockControl.style.color = 'rgb(8, 46, 170)';

      jurnal.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      jurnal.style.color = 'rgb(0, 0, 0)';
      jurnal.style.fontWeight = '500';
      jurnal.style.boxShadow = 'none';
      topJurnal.style.backgroundColor = 'rgb(49, 173, 211)';
      topJurnal.style.color = 'rgb(8, 46, 170)';

      visit.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      visit.style.color = 'rgb(0, 0, 0)';
      visit.style.fontWeight = '500';
      visit.style.boxShadow = 'none';
      topVisit.style.backgroundColor = 'rgb(49, 173, 211)';
      topVisit.style.color = 'rgb(8, 46, 170)';

   });

   dbStudent.addEventListener('click', () => {

      user.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      user.style.color = 'rgb(0, 0, 0)';
      user.style.fontWeight = '500';
      user.style.boxShadow = 'none';
      topUser.style.backgroundColor = 'rgb(49, 173, 211)';
      topUser.style.color = 'rgb(8, 46, 170)';
     
      dbStudent.style.background = 'rgb(51, 76, 155)';
      dbStudent.style.color = 'white';
      dbStudent.style.fontWeight = '600';
      dbStudent.style.boxShadow = '4px 4px 5px 4px rgba(58, 127, 231, 0.7)';
      topDbStudent.style.background = 'rgb(8, 46, 170)';
      topDbStudent.style.color = 'white';

      dbTeacher.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbTeacher.style.color = 'rgb(0, 0, 0)';
      dbTeacher.style.fontWeight = '500';
      dbTeacher.style.boxShadow = 'none';
      topDbTeacher.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbTeacher.style.color = 'rgb(8, 46, 170)';

      blockControl.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      blockControl.style.color = 'rgb(0, 0, 0)';
      blockControl.style.fontWeight = '500';
      blockControl.style.boxShadow = 'none';
      topBlockControl.style.backgroundColor = 'rgb(49, 173, 211)';
      topBlockControl.style.color = 'rgb(8, 46, 170)';

      jurnal.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      jurnal.style.color = 'rgb(0, 0, 0)';
      jurnal.style.fontWeight = '500';
      jurnal.style.boxShadow = 'none';
      topJurnal.style.backgroundColor = 'rgb(49, 173, 211)';
      topJurnal.style.color = 'rgb(8, 46, 170)';

      visit.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      visit.style.color = 'rgb(0, 0, 0)';
      visit.style.fontWeight = '500';
      visit.style.boxShadow = 'none';
      topVisit.style.backgroundColor = 'rgb(49, 173, 211)';
      topVisit.style.color = 'rgb(8, 46, 170)';

   });
   
   dbTeacher.addEventListener('click', () => {

      user.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      user.style.color = 'rgb(0, 0, 0)';
      user.style.fontWeight = '500';
      user.style.boxShadow = 'none';
      topUser.style.backgroundColor = 'rgb(49, 173, 211)';
      topUser.style.color = 'rgb(8, 46, 170)';
     
      dbStudent.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbStudent.style.color = 'rgb(0, 0, 0)';
      dbStudent.style.fontWeight = '500';
      dbStudent.style.boxShadow = 'none';
      topDbStudent.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbStudent.style.color = 'rgb(8, 46, 170)';

      dbTeacher.style.background = 'rgb(51, 76, 155)';
      dbTeacher.style.color = 'white';
      dbTeacher.style.fontWeight = '600';
      dbTeacher.style.boxShadow = '4px 4px 5px 4px rgba(58, 127, 231, 0.7)';
      topDbTeacher.style.background = 'rgb(8, 46, 170)';
      topDbTeacher.style.color = 'white';

      blockControl.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      blockControl.style.color = 'rgb(0, 0, 0)';
      blockControl.style.fontWeight = '500';
      blockControl.style.boxShadow = 'none';
      topBlockControl.style.backgroundColor = 'rgb(49, 173, 211)';
      topBlockControl.style.color = 'rgb(8, 46, 170)';

      jurnal.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      jurnal.style.color = 'rgb(0, 0, 0)';
      jurnal.style.fontWeight = '500';
      jurnal.style.boxShadow = 'none';
      topJurnal.style.backgroundColor = 'rgb(49, 173, 211)';
      topJurnal.style.color = 'rgb(8, 46, 170)';

      visit.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      visit.style.color = 'rgb(0, 0, 0)';
      visit.style.fontWeight = '500';
      visit.style.boxShadow = 'none';
      topVisit.style.backgroundColor = 'rgb(49, 173, 211)';
      topVisit.style.color = 'rgb(8, 46, 170)';

   });
   
   blockControl.addEventListener('click', () => {

      user.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      user.style.color = 'rgb(0, 0, 0)';
      user.style.fontWeight = '500';
      user.style.boxShadow = 'none';
      topUser.style.backgroundColor = 'rgb(49, 173, 211)';
      topUser.style.color = 'rgb(8, 46, 170)';
     
      dbStudent.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbStudent.style.color = 'rgb(0, 0, 0)';
      dbStudent.style.fontWeight = '500';
      dbStudent.style.boxShadow = 'none';
      topDbStudent.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbStudent.style.color = 'rgb(8, 46, 170)';

      dbTeacher.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbTeacher.style.color = 'rgb(0, 0, 0)';
      dbTeacher.style.fontWeight = '500';
      dbTeacher.style.boxShadow = 'none';
      topDbTeacher.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbTeacher.style.color = 'rgb(8, 46, 170)';

      blockControl.style.background = 'rgb(51, 76, 155)';
      blockControl.style.color = 'white';
      blockControl.style.fontWeight = '600';
      blockControl.style.boxShadow = '4px 4px 5px 4px rgba(58, 127, 231, 0.7)';
      topBlockControl.style.background = 'rgb(8, 46, 170)';
      topBlockControl.style.color = 'white';

      jurnal.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      jurnal.style.color = 'rgb(0, 0, 0)';
      jurnal.style.fontWeight = '500';
      jurnal.style.boxShadow = 'none';
      topJurnal.style.backgroundColor = 'rgb(49, 173, 211)';
      topJurnal.style.color = 'rgb(8, 46, 170)';

      visit.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      visit.style.color = 'rgb(0, 0, 0)';
      visit.style.fontWeight = '500';
      visit.style.boxShadow = 'none';
      topVisit.style.backgroundColor = 'rgb(49, 173, 211)';
      topVisit.style.color = 'rgb(8, 46, 170)';

   });
   
   jurnal.addEventListener('click', () => {

      user.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      user.style.color = 'rgb(0, 0, 0)';
      user.style.fontWeight = '500';
      user.style.boxShadow = 'none';
      topUser.style.backgroundColor = 'rgb(49, 173, 211)';
      topUser.style.color = 'rgb(8, 46, 170)';
     
      dbStudent.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbStudent.style.color = 'rgb(0, 0, 0)';
      dbStudent.style.fontWeight = '500';
      dbStudent.style.boxShadow = 'none';
      topDbStudent.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbStudent.style.color = 'rgb(8, 46, 170)';

      dbTeacher.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbTeacher.style.color = 'rgb(0, 0, 0)';
      dbTeacher.style.fontWeight = '500';
      dbTeacher.style.boxShadow = 'none';
      topDbTeacher.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbTeacher.style.color = 'rgb(8, 46, 170)';

      blockControl.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      blockControl.style.color = 'rgb(0, 0, 0)';
      blockControl.style.fontWeight = '500';
      blockControl.style.boxShadow = 'none';
      topBlockControl.style.backgroundColor = 'rgb(49, 173, 211)';
      topBlockControl.style.color = 'rgb(8, 46, 170)';

      jurnal.style.background = 'rgb(51, 76, 155)';
      jurnal.style.color = 'white';
      jurnal.style.fontWeight = '600';
      jurnal.style.boxShadow = '4px 4px 5px 4px rgba(58, 127, 231, 0.7)';
      topJurnal.style.background = 'rgb(8, 46, 170)';
      topJurnal.style.color = 'white';

      visit.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      visit.style.color = 'rgb(0, 0, 0)';
      visit.style.fontWeight = '500';
      visit.style.boxShadow = 'none';
      topVisit.style.backgroundColor = 'rgb(49, 173, 211)';
      topVisit.style.color = 'rgb(8, 46, 170)';

   });

   visit.addEventListener('click', () => {
      
      user.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      user.style.color = 'rgb(0, 0, 0)';
      user.style.fontWeight = '500';
      user.style.boxShadow = 'none';
      topUser.style.backgroundColor = 'rgb(49, 173, 211)';
      topUser.style.color = 'rgb(8, 46, 170)';
     
      dbStudent.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbStudent.style.color = 'rgb(0, 0, 0)';
      dbStudent.style.fontWeight = '500';
      dbStudent.style.boxShadow = 'none';
      topDbStudent.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbStudent.style.color = 'rgb(8, 46, 170)';

      dbTeacher.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbTeacher.style.color = 'rgb(0, 0, 0)';
      dbTeacher.style.fontWeight = '500';
      dbTeacher.style.boxShadow = 'none';
      topDbTeacher.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbTeacher.style.color = 'rgb(8, 46, 170)';

      blockControl.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      blockControl.style.color = 'rgb(0, 0, 0)';
      blockControl.style.fontWeight = '500';
      blockControl.style.boxShadow = 'none';
      topBlockControl.style.backgroundColor = 'rgb(49, 173, 211)';
      topBlockControl.style.color = 'rgb(8, 46, 170)';

      jurnal.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      jurnal.style.color = 'rgb(0, 0, 0)';
      jurnal.style.fontWeight = '500';
      jurnal.style.boxShadow = 'none';
      topJurnal.style.backgroundColor = 'rgb(49, 173, 211)';
      topJurnal.style.color = 'rgb(8, 46, 170)';

      visit.style.background = 'rgb(51, 76, 155)';
      visit.style.color = 'white';
      visit.style.fontWeight = '600';
      visit.style.boxShadow = '4px 4px 5px 4px rgba(58, 127, 231, 0.7)';
      topVisit.style.background = 'rgb(8, 46, 170)';
      topVisit.style.color = 'white';
      
   });

   topUser.addEventListener('click', () => {

      user.style.background = 'rgb(51, 76, 155)';
      user.style.color = 'white';
      user.style.fontWeight = '600';
      user.style.boxShadow = '4px 4px 5px 4px rgba(58, 127, 231, 0.7)';
      topUser.style.background = 'rgb(8, 46, 170)';
      topUser.style.color = 'white';
     
      dbStudent.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbStudent.style.color = 'rgb(0, 0, 0)';
      dbStudent.style.fontWeight = '500';
      dbStudent.style.boxShadow = 'none';
      topDbStudent.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbStudent.style.color = 'rgb(8, 46, 170)';

      dbTeacher.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbTeacher.style.color = 'rgb(0, 0, 0)';
      dbTeacher.style.fontWeight = '500';
      dbTeacher.style.boxShadow = 'none';
      topDbTeacher.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbTeacher.style.color = 'rgb(8, 46, 170)';

      blockControl.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      blockControl.style.color = 'rgb(0, 0, 0)';
      blockControl.style.fontWeight = '500';
      blockControl.style.boxShadow = 'none';
      topBlockControl.style.backgroundColor = 'rgb(49, 173, 211)';
      topBlockControl.style.color = 'rgb(8, 46, 170)';

      jurnal.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      jurnal.style.color = 'rgb(0, 0, 0)';
      jurnal.style.fontWeight = '500';
      jurnal.style.boxShadow = 'none';
      topJurnal.style.backgroundColor = 'rgb(49, 173, 211)';
      topJurnal.style.color = 'rgb(8, 46, 170)';

      visit.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      visit.style.color = 'rgb(0, 0, 0)';
      visit.style.fontWeight = '500';
      visit.style.boxShadow = 'none';
      topVisit.style.backgroundColor = 'rgb(49, 173, 211)';
      topVisit.style.color = 'rgb(8, 46, 170)';

   });

   topDbStudent.addEventListener('click', () => {

      user.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      user.style.color = 'rgb(0, 0, 0)';
      user.style.fontWeight = '500';
      user.style.boxShadow = 'none';
      topUser.style.backgroundColor = 'rgb(49, 173, 211)';
      topUser.style.color = 'rgb(8, 46, 170)';
     
      dbStudent.style.background = 'rgb(51, 76, 155)';
      dbStudent.style.color = 'white';
      dbStudent.style.fontWeight = '600';
      dbStudent.style.boxShadow = '4px 4px 5px 4px rgba(58, 127, 231, 0.7)';
      topDbStudent.style.background = 'rgb(8, 46, 170)';
      topDbStudent.style.color = 'white';

      dbTeacher.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbTeacher.style.color = 'rgb(0, 0, 0)';
      dbTeacher.style.fontWeight = '500';
      dbTeacher.style.boxShadow = 'none';
      topDbTeacher.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbTeacher.style.color = 'rgb(8, 46, 170)';

      blockControl.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      blockControl.style.color = 'rgb(0, 0, 0)';
      blockControl.style.fontWeight = '500';
      blockControl.style.boxShadow = 'none';
      topBlockControl.style.backgroundColor = 'rgb(49, 173, 211)';
      topBlockControl.style.color = 'rgb(8, 46, 170)';

      jurnal.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      jurnal.style.color = 'rgb(0, 0, 0)';
      jurnal.style.fontWeight = '500';
      jurnal.style.boxShadow = 'none';
      topJurnal.style.backgroundColor = 'rgb(49, 173, 211)';
      topJurnal.style.color = 'rgb(8, 46, 170)';

      visit.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      visit.style.color = 'rgb(0, 0, 0)';
      visit.style.fontWeight = '500';
      visit.style.boxShadow = 'none';
      topVisit.style.backgroundColor = 'rgb(49, 173, 211)';
      topVisit.style.color = 'rgb(8, 46, 170)';

   });
   
   topDbTeacher.addEventListener('click', () => {

      user.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      user.style.color = 'rgb(0, 0, 0)';
      user.style.fontWeight = '500';
      user.style.boxShadow = 'none';
      topUser.style.backgroundColor = 'rgb(49, 173, 211)';
      topUser.style.color = 'rgb(8, 46, 170)';
     
      dbStudent.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbStudent.style.color = 'rgb(0, 0, 0)';
      dbStudent.style.fontWeight = '500';
      dbStudent.style.boxShadow = 'none';
      topDbStudent.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbStudent.style.color = 'rgb(8, 46, 170)';

      dbTeacher.style.background = 'rgb(51, 76, 155)';
      dbTeacher.style.color = 'white';
      dbTeacher.style.fontWeight = '600';
      dbTeacher.style.boxShadow = '4px 4px 5px 4px rgba(58, 127, 231, 0.7)';
      topDbTeacher.style.background = 'rgb(8, 46, 170)';
      topDbTeacher.style.color = 'white';

      blockControl.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      blockControl.style.color = 'rgb(0, 0, 0)';
      blockControl.style.fontWeight = '500';
      blockControl.style.boxShadow = 'none';
      topBlockControl.style.backgroundColor = 'rgb(49, 173, 211)';
      topBlockControl.style.color = 'rgb(8, 46, 170)';

      jurnal.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      jurnal.style.color = 'rgb(0, 0, 0)';
      jurnal.style.fontWeight = '500';
      jurnal.style.boxShadow = 'none';
      topJurnal.style.backgroundColor = 'rgb(49, 173, 211)';
      topJurnal.style.color = 'rgb(8, 46, 170)';

      visit.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      visit.style.color = 'rgb(0, 0, 0)';
      visit.style.fontWeight = '500';
      visit.style.boxShadow = 'none';
      topVisit.style.backgroundColor = 'rgb(49, 173, 211)';
      topVisit.style.color = 'rgb(8, 46, 170)';


   });
   
   topBlockControl.addEventListener('click', () => {

      user.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      user.style.color = 'rgb(0, 0, 0)';
      user.style.fontWeight = '500';
      user.style.boxShadow = 'none';
      topUser.style.backgroundColor = 'rgb(49, 173, 211)';
      topUser.style.color = 'rgb(8, 46, 170)';
     
      dbStudent.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbStudent.style.color = 'rgb(0, 0, 0)';
      dbStudent.style.fontWeight = '500';
      dbStudent.style.boxShadow = 'none';
      topDbStudent.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbStudent.style.color = 'rgb(8, 46, 170)';

      dbTeacher.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbTeacher.style.color = 'rgb(0, 0, 0)';
      dbTeacher.style.fontWeight = '500';
      dbTeacher.style.boxShadow = 'none';
      topDbTeacher.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbTeacher.style.color = 'rgb(8, 46, 170)';

      blockControl.style.background = 'rgb(51, 76, 155)';
      blockControl.style.color = 'white';
      blockControl.style.fontWeight = '600';
      blockControl.style.boxShadow = '4px 4px 5px 4px rgba(58, 127, 231, 0.7)';
      topBlockControl.style.background = 'rgb(8, 46, 170)';
      topBlockControl.style.color = 'white';

      jurnal.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      jurnal.style.color = 'rgb(0, 0, 0)';
      jurnal.style.fontWeight = '500';
      jurnal.style.boxShadow = 'none';
      topJurnal.style.backgroundColor = 'rgb(49, 173, 211)';
      topJurnal.style.color = 'rgb(8, 46, 170)';

      visit.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      visit.style.color = 'rgb(0, 0, 0)';
      visit.style.fontWeight = '500';
      visit.style.boxShadow = 'none';
      topVisit.style.backgroundColor = 'rgb(49, 173, 211)';
      topVisit.style.color = 'rgb(8, 46, 170)';

   });
   
   topJurnal.addEventListener('click', () => {

      user.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      user.style.color = 'rgb(0, 0, 0)';
      user.style.fontWeight = '500';
      user.style.boxShadow = 'none';
      topUser.style.backgroundColor = 'rgb(49, 173, 211)';
      topUser.style.color = 'rgb(8, 46, 170)';
     
      dbStudent.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbStudent.style.color = 'rgb(0, 0, 0)';
      dbStudent.style.fontWeight = '500';
      dbStudent.style.boxShadow = 'none';
      topDbStudent.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbStudent.style.color = 'rgb(8, 46, 170)';

      dbTeacher.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbTeacher.style.color = 'rgb(0, 0, 0)';
      dbTeacher.style.fontWeight = '500';
      dbTeacher.style.boxShadow = 'none';
      topDbTeacher.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbTeacher.style.color = 'rgb(8, 46, 170)';

      blockControl.style.background = 'rgb(51, 76, 155)';
      blockControl.style.color = 'white';
      blockControl.style.fontWeight = '600';
      blockControl.style.boxShadow = '4px 4px 5px 4px rgba(58, 127, 231, 0.7)';
      topBlockControl.style.background = 'rgb(8, 46, 170)';
      topBlockControl.style.color = 'white';

      jurnal.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      jurnal.style.color = 'rgb(0, 0, 0)';
      jurnal.style.fontWeight = '500';
      jurnal.style.boxShadow = 'none';
      topJurnal.style.backgroundColor = 'rgb(49, 173, 211)';
      topJurnal.style.color = 'rgb(8, 46, 170)';

      visit.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      visit.style.color = 'rgb(0, 0, 0)';
      visit.style.fontWeight = '500';
      visit.style.boxShadow = 'none';
      topVisit.style.backgroundColor = 'rgb(49, 173, 211)';
      topVisit.style.color = 'rgb(8, 46, 170)';


   });
   
   topVisit.addEventListener('click', () => {

      user.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      user.style.color = 'rgb(0, 0, 0)';
      user.style.fontWeight = '500';
      user.style.boxShadow = 'none';
      topUser.style.backgroundColor = 'rgb(49, 173, 211)';
      topUser.style.color = 'rgb(8, 46, 170)';
     
      dbStudent.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbStudent.style.color = 'rgb(0, 0, 0)';
      dbStudent.style.fontWeight = '500';
      dbStudent.style.boxShadow = 'none';
      topDbStudent.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbStudent.style.color = 'rgb(8, 46, 170)';

      dbTeacher.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      dbTeacher.style.color = 'rgb(0, 0, 0)';
      dbTeacher.style.fontWeight = '500';
      dbTeacher.style.boxShadow = 'none';
      topDbTeacher.style.backgroundColor = 'rgb(49, 173, 211)';
      topDbTeacher.style.color = 'rgb(8, 46, 170)';

      blockControl.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      blockControl.style.color = 'rgb(0, 0, 0)';
      blockControl.style.fontWeight = '500';
      blockControl.style.boxShadow = 'none';
      topBlockControl.style.backgroundColor = 'rgb(49, 173, 211)';
      topBlockControl.style.color = 'rgb(8, 46, 170)';

      jurnal.style.background = 'rgb(51, 76, 155)';
      jurnal.style.color = 'white';
      jurnal.style.fontWeight = '600';
      jurnal.style.boxShadow = '4px 4px 5px 4px rgba(58, 127, 231, 0.7)';
      topJurnal.style.background = 'rgb(8, 46, 170)';
      topJurnal.style.color = 'white';

      visit.style.background = 'linear-gradient(to right, rgb(49, 163, 201), rgb(99, 213, 251), rgb(149, 255, 255))';
      visit.style.color = 'rgb(0, 0, 0)';
      visit.style.fontWeight = '500';
      visit.style.boxShadow = 'none';
      topVisit.style.backgroundColor = 'rgb(49, 173, 211)';
      topVisit.style.color = 'rgb(8, 46, 170)';

   });

});