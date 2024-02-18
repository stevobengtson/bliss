class HomeController < ApplicationController
  layout "simple"
  before_action :authenticate_user!

  def index
  end
end
