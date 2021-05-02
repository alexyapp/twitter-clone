<template>
  <b-container>
    <b-row>
      <b-col lg="6" offset-lg="3" fluid="md">
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