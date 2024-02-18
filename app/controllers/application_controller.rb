# frozen_string_literal: true

class ApplicationController < ActionController::Base
  before_action :set_budget

  private

  def set_budget
    return unless params[:budget_id] && current_user

    @budget = current_user.budgets.find(params[:budget_id])
  end
end
