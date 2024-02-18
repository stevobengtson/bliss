# frozen_string_literal: true

class CreateAccounts < ActiveRecord::Migration[7.1]
  def change
    create_table :accounts, id: :uuid do |t|
      t.string :name
      t.integer :balance
      t.integer :account_type
      t.references :user, null: false, foreign_key: true, type: :uuid
      t.references :budget, null: false, foreign_key: true, type: :uuid

      t.timestamps
    end
  end
end
