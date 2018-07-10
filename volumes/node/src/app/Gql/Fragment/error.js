/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

import gql from "graphql-tag";

export let ErrorFragment = gql`
    fragment ErrorFragment on FormError {
        key
        message
    }
`;
