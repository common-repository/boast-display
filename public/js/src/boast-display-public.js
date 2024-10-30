class BoastDisplay {
  constructor(display_element) {
    this.display_element = display_element;
    this.selectors = display_element.querySelectorAll('.selector');
    this.cards = display_element.querySelectorAll('.boast-card');
    this.videos = display_element.querySelectorAll('video');
    this.cards_wrapper = display_element.querySelector('.cards');
    this.timeout = display_element.dataset.timeout * 1000;
    this.offset = 0;
    this.t;

    this.initializeTimeout = this.initializeTimeout.bind(this);
    this.initializeSelectors = this.initializeSelectors.bind(this);
    this.initializeVideos = this.initializeVideos.bind(this);
    this.updateCardPosition = this.updateCardPosition.bind(this);
    this.updateCardsHeight = this.updateCardsHeight.bind(this);
    this.slide = this.slide.bind(this);
  }

  initializeTimeout() {
    if (this.timeout > 0) {
      clearTimeout(this.t);
      this.t = setTimeout(this.slide, this.timeout);
    }
  }

  initializeSelectors() {
    let that = this;
    for (let selector of this.selectors) {
      selector.addEventListener("click", function() {
        // Update offset
        that.offset = this.dataset.index;

        // Reset timeout
        that.initializeTimeout();

        // Move cards
        that.updateCardPosition();

        // Adjust cards height
        that.updateCardsHeight();

        // Change indicator
        that.display_element.querySelector('.active').classList.remove('active');
        this.classList.add('active');

        // Pause running videos
        for (let video of that.videos) {
          video.pause();
        }
      });
    }
  }

  initializeVideos() {
    let that = this;
    for (let video of this.videos) {
      video.addEventListener("playing", function() {
        this.dataset.playing = true;
      });

      video.addEventListener("pause", function() {
        this.dataset.playing = false;
        that.initializeTimeout();
      });
    }
  }

  updateCardPosition() {
    for (let card of this.cards) {
      card.style.left = -100 * this.offset + "%";
    }
  }

  updateCardsHeight() {
    let height = this.cards[this.offset].clientHeight;
    if (this.cards_wrapper.classList.contains('auto_height')) {
      this.cards_wrapper.style.maxHeight = height + "px";
    }
  }

  slide() {
    let activeVideos = this.display_element.querySelectorAll('[data-playing="true"]');
    if (activeVideos.length === 0 && this.selectors.length > 1) {
      // Update offset
      if (this.offset >= this.cards.length - 1) {
        this.offset = 0;
      } else {
        this.offset++;
      }

      // Update card position
      this.updateCardPosition();

      // Update cards height
      this.updateCardsHeight();

      // Remove active from old selector
      this.display_element.querySelector('.active').classList.remove('active');

      // Add active to new selector
      this.display_element.querySelector(`[data-index='${this.offset}']`).classList.add('active');
    }

    this.t = setTimeout(this.slide, this.timeout);
  }
}

window.addEventListener("load", function() {
  let displays = document.querySelectorAll('.boast-display');
  for (let display of displays) {
    let boast_display = new BoastDisplay(display);
    boast_display.initializeTimeout();
    boast_display.initializeSelectors();
    boast_display.initializeVideos();
  }
});
