<?php

namespace App\Http\Controllers;

use App\Graphql\GraphQLDefinition;
use Illuminate\Http\Request;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;

class GraphQLController extends Controller
{

    //
    public function graphqlEndpoint(Request $request)
    {

        $schema = new Schema([
            'query' => GraphQLDefinition::query(),
            'mutation' => NULL,
        ]);

        try {
            $rootVal = NULL; //['prefix' => 'You said: '];
            $query   = $request->input('query');
            $args    = $request->input('variables') ?? NULL;
            $result  = GraphQL::executeQuery($schema, $query, $rootVal, null, $args);
            $output  = $result->toArray();
        } catch (\Exception $e) {
            $output = [
                'errors' => [
                    [
                        'message' => $e->getMessage()
                    ]
                ]
            ];
        }
        return response()->json($output);
    }
}
