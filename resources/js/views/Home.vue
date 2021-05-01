<template>
  <b-container>
    <b-row>
      <b-col cols="6" offset="3">
        <tweet-list></tweet-list>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import TweetList from '../components/TweetList';

export default {
  components: {
    TweetList,
  },
}
</script>

<style>

</style>