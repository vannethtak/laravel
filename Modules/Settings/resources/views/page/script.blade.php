<script>
    $(document).ready(function() {
        // When the "Create" button is clicked
        $('#add-row-btn').on('click', function(e) {
            e.preventDefault(); // Prevent default action of the button

            // Create a new row template <option value="top-left">@lang('top-left')</option>
            var newRow = `
                <tr>
                    <td class="p-0">
                        <input type="text" class="form-control m-0" name="action_name_en[]" placeholder="@lang('enter_')@lang('action_name_en')" />
                    </td>
                    <td class="p-0">
                        <input type="text" class="form-control m-0" name="action_name_kh[]" placeholder="@lang('enter_')@lang('action_name_kh')" />
                    </td>
                    <td class="p-0">
                        <input type="text" class="form-control m-0" name="action_route[]" placeholder="@lang('enter_')@lang('action_route')" />
                    </td>
                    <td class="p-0">
                        <input type="text" class="form-control m-0" name="action_type[]" placeholder="@lang('enter_')@lang('action_type')" />
                    </td>
                    <td class="p-0">
                        <select class="form-control m-0" name="action_position[]">
                            @foreach (\App\Enums\ActionPositionEnum::casesArray() as $key => $label)
                                <option value="{{ $key }}">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="p-0">
                        <input type="text" class="form-control m-0" id="action_icon" name="action_icon" placeholder="@lang('enter_')@lang('action_icon')" value="{{ request('action_icon') }}" />
                    </td>
                    <td class="p-0">
                        <select class="form-control m-0" id="action_active" name="action_active[]">
                            <option value="Y">@lang('active_yes')</option>
                            <option value="N">@lang('active_no')</option>
                        </select>
                    </td>
                    <td class="p-0">
                        <a href="#" class="btn btn-danger m-0 remove-row d-flex align-items-center justify-content-center">
                            <i class="fas fa-trash me-2"></i><span>@lang('delete')</span>
                        </a>
                    </td>
                </tr>
            `;

            // Append the new row to the table
            $('table tbody').append(newRow);
        });

        // Event delegation for removing rows
        $('table').on('click', '.remove-row', function(e) {
            e.preventDefault(); // Prevent the default action
            $(this).closest('tr').remove(); // Remove the row
        });
    });

</script>
