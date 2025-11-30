<div>
    <div class="w-full overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-primary uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-primary uppercase tracking-wider">Email
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-primary uppercase tracking-wider">Login -
                        UUID
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-primary uppercase tracking-wider">Login -
                        Username</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-primary uppercase tracking-wider">Login -
                        Password
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-primary uppercase tracking-wider">Phone
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-primary uppercase tracking-wider">Cell
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-primary uppercase tracking-wider">Picture
                    </th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($agent as $ag)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-primary">{{ $ag->name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-primary">{{ $ag->email }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-primary">{{ $ag->uuid }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-primary">{{ $ag->username }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-primary">{{ $ag->password }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-primary">{{ $ag->phone }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-primary">{{ $ag->cell }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                           <img src="{{ $ag->picture }}" alt="" class="w-10">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>