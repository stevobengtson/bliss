# frozen_string_literal: true

json.extract! transaction, :id, :account_id, :user_id, :memo, :cleared, :amount, :running_balance, :budget_id,
              :entry_date, :created_at, :updated_at
json.url transaction_url(transaction, format: :json)
