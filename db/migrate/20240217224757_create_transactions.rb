# frozen_string_literal: true

class CreateTransactions < ActiveRecord::Migration[7.1]
  def change
    create_table :transactions, id: :uuid do |t|
      t.string :memo
      t.boolean :cleared, default: false
      t.integer :amount, null: false
      t.datetime :entry_date, null: false
      t.references :account, null: false, foreign_key: true, type: :uuid
      t.references :user, null: false, foreign_key: true, type: :uuid

      t.timestamps
    end
  end
end
