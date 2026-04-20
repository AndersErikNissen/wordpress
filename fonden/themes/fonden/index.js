"use strict";

(function(d) {
  let accordionCollections = d.querySelectorAll(".accordion-collection");
  if ( accordionCollections.length === 0 ) return;
  accordionCollections.forEach((collection) => {
    let accordions = collection.querySelectorAll(".accordion");
    accordions.forEach((accordion) => {
      let header = accordion.querySelector(".accordion__header");
      if (header) {
        header.addEventListener("click", () => {
          let isOpen = JSON.parse(accordion.dataset.open || false);
          accordion.setAttribute('data-open', JSON.stringify(!isOpen));
        });
      }
    });
  });
})(document);