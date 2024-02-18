# frozen_string_literal: true

class AccountsController < ApplicationController
  before_action :authenticate_user!
  before_action :set_budget, only: %i[index new create]
  before_action :set_account, only: %i[destroy]

  # GET /budgets/:budget_id/accounts or /budgets/:budget_id/accounts.json
  def index
    @accounts = @budget.accounts.limit(30).order(name: :asc)
  end

  # GET /budgets/:budget_id/accounts/new
  def new
    @account = @budget.accounts.new
  end

  # POST /budgets/:budget_id/accounts or /budgets/:budget_id/accounts.json
  def create
    @account = @budget.accounts.new(account_params)
    @account.user = current_user

    respond_to do |format|
      if @budget.save
        format.html { redirect_to budget_accounts_url(@budget), notice: 'Account was successfully created.' }
        format.json { render :show, status: :created, location: @account }
      else
        format.html { render :new, status: :unprocessable_entity }
        format.json { render json: @account.errors, status: :unprocessable_entity }
      end
    end
  end

  def destroy
    @account.destroy!

    respond_to do |format|
      format.html do
        redirect_to budget_accounts_url(@account.budget), notice: 'Account was successfully destroyed.'
      end
      format.json { head :no_content }
    end
  end

  private

  def set_budget
    @budget = current_user.budgets.find(params[:budget_id])
  end

  def set_account
    @account = Account.find(params[:id])
  end

  def account_params
    params.require(:account).permit(:name)
  end
end
