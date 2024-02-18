# frozen_string_literal: true

class Transaction < ApplicationRecord
  belongs_to :account
  belongs_to :user

  composed_of :amount,
              class_name: 'Money',
              mapping: %w[amount cents],
              converter: proc { |value| Money.new(value) }

  self.implicit_order_column = 'entry_date'

  paginates_per 25
end
