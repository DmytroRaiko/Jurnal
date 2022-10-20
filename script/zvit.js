document.addEventListener('DOMContentLoaded', () => {
   'use strict';

   const zvitMonthPropusk = document.getElementById('zvit-month-propusk');

   zvitMonthPropusk.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order-zvit-month-propusk');
   
      if (target.closest('.x-zvit-month-propusk') || (target === modal)) {
         zvitMonthPropusk.style.display = 'none';
      }
   });

   const zvit2 = document.getElementById('zvit-vidomist');

   zvit2.addEventListener('click', event => {
      const target = event.target;
      const modal = target.closest('.order-zvit-vidimist');
   
      if (target.closest('.x-zvit-vidimist') || (target === modal)) {
         zvit2.style.display = 'none';
      }
   });

});
