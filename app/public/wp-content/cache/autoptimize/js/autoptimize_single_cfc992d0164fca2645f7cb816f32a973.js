document.addEventListener('DOMContentLoaded',function(){const hero=document.querySelector('.hero');const wordContainer=document.querySelector('.rotated-word');if(!wordContainer){console.error('wordContainer is null');return;}
const word=wordContainer.getAttribute('data-word');function revealHero(){hero.style.opacity='1';hero.style.transform='translateY(0)';}
function animateLetters(){word.split('').forEach((letter,index)=>{const span=document.createElement('span');span.className='letter';span.textContent=letter===' '?'\u00A0':letter;if(letter===' '){span.classList.add('space');}
wordContainer.appendChild(span);if(letter!==' '){setTimeout(()=>{span.classList.add('visible');},index*100);}});}
setTimeout(revealHero,500);setTimeout(animateLetters,2500);});