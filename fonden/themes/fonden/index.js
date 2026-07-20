"use strict";

class AccordionCollection {
  constructor(collection) {
    this.collection = collection;

    if (!this.collection) return;

    this.accordions = this.collection.querySelectorAll(".accordion");

    if (this.accordions.length === 0) return;

    this.bindAccordions();
  }

  bindAccordions() {
    this.accordions.forEach((accordion) => {
      let header = accordion.querySelector(".accordion__header");

      if (header) {
        header.addEventListener("click", () => {
          let isOpen = JSON.parse(accordion.dataset.open || false);
          accordion.setAttribute('data-open', JSON.stringify(!isOpen));
        });
      }
    });
  }
}

const ACCORDION_COLLECTIONS = Array.from(document.querySelectorAll(".accordion-collection")).map((collection) => {
  return new AccordionCollection(collection);
});

class Modal {
  _cooldown = false;
  _isOpen = false;

  constructor({modal, buttons = []}) {
    if (!modal || buttons.length === 0) return;
    
    this.modal = modal;
    this.buttons = buttons;
    this.binds();
  }

  get cooldown() {
    return this._cooldown;
  }
  
  set cooldown(time) {
    this._cooldown = setTimeout(() => {
      this._cooldown = false;

      if (!this.isOpen) {
        this.modal.classList.add("hidden");
      }
    }, time);
  }

  get isOpen() {
    return this._isOpen;
  }

  set isOpen(bool) {    
    if (this.cooldown || bool === this.isOpen) return;

    this.cooldown = bool ? 701 : 351;

    this._isOpen = bool;
    this.modal.setAttribute("data-is-open", JSON.stringify(bool));

    if (bool) {
      this.modal.classList.remove("hidden");
    }
  }

  binds() {
    document.body.addEventListener("keyup", (e) => {
      if (e.key === "Escape") {
        this.isOpen = false;
      }
    });

    this.modal.addEventListener("click", (e) => {
      if (e.target === this.modal) {
        this.isOpen = false;
      }
    });

    this.buttons.forEach((button) => {
      button.addEventListener("click", () => {
        this.isOpen = !this.isOpen;
      });
    });
  }
}

const MENU = new Modal({
  modal: document.querySelector('.modal.menu'),
  buttons: document.querySelectorAll('[data-modal-btn="menu"]'),
});

const APPLY = new Modal({
  modal: document.querySelector('.modal.apply'),
  buttons: document.querySelectorAll('[data-modal-btn="apply"]'),
});