<template>
  <div>
    <svg
      id="stars"
      style="display: none;"
      version="1.1"
    >
      <symbol
        id="stars-full-star"
        viewBox="0 0 102 18"
      >
        <path d="M9.5 14.25l-5.584 2.936 1.066-6.218L.465 6.564l6.243-.907L9.5 0l2.792 5.657 6.243.907-4.517 4.404 1.066 6.218" />
      </symbol>

      <symbol
        id="stars-half-star"
        viewBox="0 0 102 18"
      >
        <path
          d="M9.5 14.25l-5.584 2.936 1.066-6.218L.465 6.564l6.243-.907L9.5 0l2.792"
          fill="#dd6d74"
        />
      </symbol>

      <symbol
        id="stars-all-star"
        viewBox="0 0 102 18"
      >
        <use xlink:href="#stars-full-star" />
        <use
          xlink:href="#stars-full-star"
          transform="translate(21)"
        />
        <use
          xlink:href="#stars-full-star"
          transform="translate(42)"
        />
        <use
          xlink:href="#stars-full-star"
          transform="translate(63)"
        />
        <use
          xlink:href="#stars-full-star"
          transform="translate(84)"
        />
      </symbol>

      <symbol
        id="stars-0-0-star"
        viewBox="0 0 102 18"
      >
        <use
          xlink:href="#stars-all-star"
          fill="#9b9b9b"
        />
      </symbol>

      <symbol
        id="stars-0-5-star"
        viewBox="0 0 102 18"
      >
        <use xlink:href="#stars-0-0-star" />
        <use xlink:href="#stars-half-star" />
      </symbol>

      <symbol
        id="stars-1-0-star"
        viewBox="0 0 102 18"
      >
        <use xlink:href="#stars-0-0-star" />
        <use
          xlink:href="#stars-full-star"
          fill="#dd6d74"
        />
      </symbol>

      <symbol
        id="stars-1-5-star"
        viewBox="0 0 102 18"
      >
        <use xlink:href="#stars-1-0-star" />
        <use
          xlink:href="#stars-half-star"
          transform="translate(21)"
        />
      </symbol>

      <symbol
        id="stars-2-0-star"
        viewBox="0 0 102 18"
      >
        <use xlink:href="#stars-1-0-star" />
        <use
          xlink:href="#stars-full-star"
          fill="#dd6d74"
          transform="translate(21)"
        />
      </symbol>

      <symbol
        id="stars-2-5-star"
        viewBox="0 0 102 18"
      >
        <use xlink:href="#stars-2-0-star" />
        <use
          xlink:href="#stars-half-star"
          transform="translate(42)"
        />
      </symbol>

      <symbol
        id="stars-3-0-star"
        viewBox="0 0 102 18"
      >
        <use xlink:href="#stars-2-0-star" />
        <use
          xlink:href="#stars-full-star"
          fill="#dd6d74"
          transform="translate(42)"
        />
      </symbol>

      <symbol
        id="stars-3-5-star"
        viewBox="0 0 102 18"
      >
        <use xlink:href="#stars-3-0-star" />
        <use
          xlink:href="#stars-half-star"
          transform="translate(63)"
        />
      </symbol>

      <symbol
        id="stars-4-0-star"
        viewBox="0 0 102 18"
      >
        <use xlink:href="#stars-3-0-star" />
        <use
          xlink:href="#stars-full-star"
          fill="#dd6d74"
          transform="translate(63)"
        />
      </symbol>

      <symbol
        id="stars-4-5-star"
        viewBox="0 0 102 18"
      >
        <use xlink:href="#stars-4-0-star" />
        <use
          xlink:href="#stars-half-star"
          transform="translate(84)"
        />
      </symbol>

      <symbol
        id="stars-5-0-star"
        viewBox="0 0 102 18"
      >
        <use
          xlink:href="#stars-all-star"
          fill="#dd6d74"
        />
      </symbol>
    </svg>

    <main
      id="main"
      class="w-3/4 p-0"
    >
      <svg
        v-if="rating == 0"
        class="icon w-1/2 block h-6"
      >
        <use xlink:href="#stars-0-0-star" />
      </svg>

      <svg
        v-if="rating == 0.5"
        class="icon w-1/2 block h-6"
      >
        <use xlink:href="#stars-0-5-star" />
      </svg>

      <svg
        v-if="rating == 1"
        class="icon w-1/2 block h-6"
      >
        <use xlink:href="#stars-1-0-star" />
      </svg>

      <svg
        v-if="rating == 1.5"
        class="icon w-1/2 block h-6"
      >
        <use xlink:href="#stars-1-5-star" />
      </svg>

      <svg
        v-if="rating == 2"
        class="icon w-1/2 block h-6"
      >
        <use xlink:href="#stars-2-0-star" />
      </svg>

      <svg
        v-if="rating == 2.5"
        class="icon w-1/2 block h-6"
      >
        <use xlink:href="#stars-2-5-star" />
      </svg>

      <svg
        v-if="rating == 3"
        class="icon w-1/2 block h-6"
      >
        <use xlink:href="#stars-3-0-star" />
      </svg>

      <svg
        v-if="rating == 3.5"
        class="icon w-1/2 block h-6"
      >
        <use xlink:href="#stars-3-5-star" />
      </svg>

      <svg
        v-if="rating == 4"
        class="icon w-1/2 block h-6"
      >
        <use xlink:href="#stars-4-0-star" />
      </svg>

      <svg
        v-if="rating == 4.5"
        class="icon w-1/2 block h-6"
      >
        <use xlink:href="#stars-4-5-star" />
      </svg>

      <svg
        v-if="rating == 5"
        class="icon w-1/2 block h-6"
      >
        <use xlink:href="#stars-5-0-star" />
      </svg>
    </main>
  </div>
</template>

<script>
export default {
  name: 'RatingStars',
  props: {
    rating: {
      type: Number,
      default: 0,
    },
  },
}
</script>
