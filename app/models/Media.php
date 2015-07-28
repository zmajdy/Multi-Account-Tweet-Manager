<?php

use Illuminate\Database\Eloquent\Model;
use Abraham\TwitterOAuth\TwitterOAuth;
use \TwitterAccount;

/**
 * integer $id
 * string $name
 * string $url
 * timestamp updated_at
 * timestamp created_at
 */
class Media extends Model
{
    protected $table = 'medias';

    public function tweets() {
        return $this->belongsToMany('Tweet', 'TweetMedia', 'media_id', 'tweet_id');
    }

    public static function upload($base64)
    {
        $connection = new TwitterOAuth(
            TwitterAccount::getCredentialsTwitter()['consumer_key'],
            TwitterAccount::getCredentialsTwitter()['consumer_secret'],
            TwitterAccount::getCredentialsTwitter()['oauth_token'],
            TwitterAccount::getCredentialsTwitter()['oauth_token_secret']);
        $media1 = $connection->upload('media/upload', array('media' => $base64));
        $parameters = array(
            'status' => '',
            'media_ids' => implode(',', array($media1->media_id_string)),
        );
        $result = $connection->post('statuses/update', $parameters);
        return $result;
    }
}
