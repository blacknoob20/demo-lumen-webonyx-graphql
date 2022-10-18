<?php

namespace App\Http\Controllers;

use App\Graphql\GraphQLDefinition;
use Illuminate\Http\Request;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Illuminate\Http\Response;

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
            return response()->json($output, Response::HTTP_OK);
        } catch (\Exception $e) {
            $output = [
                'errors' => [
                    [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage()
                    ]
                ]
            ];
            return response()->json($output, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
