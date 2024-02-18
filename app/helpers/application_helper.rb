# frozen_string_literal: true

module ApplicationHelper
  def flash_class(level)
    bootstrap_alert_class = {
      'success' => 'alert-success',
      'error' => 'alert-danger',
      'notice' => 'alert-info',
      'alert' => 'alert-danger',
      'warn' => 'alert-warning'
    }
    bootstrap_alert_class[level]
  end

  def avatar_url(user)
    gravatar_id = Digest::MD5.hexdigest(user.email).downcase
    "https://gravatar.com/avatar/#{gravatar_id}.png?s=32"
    # "https://secure.gravatar.com/avatar/#{hash}.png?s=#{size}"
  end
end
