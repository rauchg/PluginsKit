<?php
/**
 * Displays a gravatar image for a given email
 *
 * @param string  $email            Email of the gravatar
 * @param string  $gravatar_rating  Maximal rating of the gravatar
 * @param integer $gravatar_size    size of the gravatar
 * @param string  $alt_text         Alternative text
 * @return string
 * @see http://site.gravatar.com/site/implement#section_1_1
 */
function gravatar_image_tag($email, $gravatar_rating = null, $gravatar_size = null, $alt_text = 'Gravatar photo')
{
  $gravatar = new GravatarApi($gravatar_rating, $gravatar_size);
  // return the gravatar image

  return image_tag($gravatar->getGravatar($email),
                   array('alt' => $alt_text,
                         'width' => sfConfig::get('app_gravatar_default_size', 80),
                         'height' => sfConfig::get('app_gravatar_default_size', 80),
                         'class' => 'gravatar_photo'
                        )
                  );
}