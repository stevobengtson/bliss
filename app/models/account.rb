# frozen_string_literal: true

class Account < ApplicationRecord
  belongs_to :budget
  belongs_to :user
  has_many :transactions, dependent: :destroy

  composed_of :balance,
              class_name: 'Money',
              mapping: %w[balance cents],
              converter: proc { |value| Money.new(value) }

  self.implicit_order_column = 'name'
end
